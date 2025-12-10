<?php

namespace App\Http\Controllers;

use App\Models\ChatHistory;
use App\Models\SuratTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate(['message' => 'required|string']);
        
        $user = Auth::user();
        $userId = $user ? $user->id : null;
        $sessionId = Session::getId();

        $pesanUser = $request->message;

        try {
            // 1. Simpan Pesan User
            ChatHistory::create([
                'user_id'    => $userId,
                'session_id' => $sessionId,
                'role'       => 'user',
                'message'    => $pesanUser,
            ]);

            // 2. Ambil History Chat
            $historyData = ChatHistory::query()
                ->where(function($q) use ($userId, $sessionId) {
                    if ($userId) {
                        $q->where('user_id', $userId);
                    } else {
                        $q->where('session_id', $sessionId);
                    }
                })
                ->latest()
                ->take(10)
                ->get()
                ->reverse();

            $history = [];
            foreach ($historyData as $chat) {
                $history[] = [
                    'role' => $chat->role === 'user' ? 'user' : 'model',
                    'parts' => [['text' => (string) $chat->message]]
                ];
            }

            // 3. Konfigurasi API Gemini
            $apiKey = env('GEMINI_API_KEY');
            if (!$apiKey) {
                Log::error('GEMINI_API_KEY tidak ditemukan di .env');
                throw new \Exception("Konfigurasi server belum lengkap.");
            }

            // MENGGUNAKAN MODEL YANG ANDA MINTA
            $model = "gemini-1.0-pro"; 

            $systemInstruction = $this->getSystemInstruction();

            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                    'contents' => $history,
                ]);

            if ($response->failed()) {
                // Log detail error dari Google untuk debugging
                Log::error('Gemini API Error: ' . $response->body());
                $aiReply = "Maaf, saat ini saya tidak dapat terhubung ke server otak saya. (Error: API)";
            } else {
                $data = $response->json();
                // Pastikan ada 'candidates' dan 'content' sebelum mengakses
                $aiReply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, saya tidak mengerti.';
            }

        } catch (\Exception $e) {
            Log::error("Chat Controller Error: " . $e->getMessage());
            $aiReply = "Terjadi kesalahan sistem internal.";
        }

        // 4. Simpan Jawaban AI
        ChatHistory::create([
            'user_id'    => $userId,
            'session_id' => $sessionId,
            'role'       => 'model',
            'message'    => $aiReply,
        ]);

        return response()->json(['reply' => $aiReply]);
    }

    private function getSystemInstruction()
    {
        // Ambil daftar surat agar AI tahu konteks
        $daftarSurat = SuratTemplate::where('is_active', true)
            ->pluck('judul_surat')
            ->implode(', ');

        return "Kamu adalah 'SiDesa', asisten virtual Desa Smart Digital yang ramah dan membantu.
        
        Tugasmu:
        1. Menjawab pertanyaan warga seputar administrasi desa.
        2. Memberikan informasi tentang layanan surat yang tersedia.
        
        Konteks Desa:
        - Layanan Surat Online yang tersedia: {$daftarSurat}.
        - Jam Kerja Kantor: Senin-Jumat, 08.00 - 15.00 WIB.
        - Alamat: Jl. Raya Desa No. 1, Kecamatan Maju Jaya.
        
        Gaya Bicara:
        - Gunakan Bahasa Indonesia yang sopan namun santai.
        - Jawaban harus singkat, padat, dan langsung ke inti (maksimal 3 paragraf).
        - Jika warga bertanya hal di luar administrasi desa, jawab dengan sopan bahwa kamu hanya bisa membantu urusan desa.";
    }

    public function getHistory()
    {
        $userId = Auth::id();
        $sessionId = Session::getId();

        return ChatHistory::query()
            ->where(function($q) use ($userId, $sessionId) {
                if ($userId) {
                    $q->where('user_id', $userId);
                } else {
                    $q->where('session_id', $sessionId);
                }
            })
            ->latest()
            ->take(20)
            ->get()
            ->reverse()
            ->values();
    }
}