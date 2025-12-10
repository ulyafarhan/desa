<?php

namespace App\Http\Controllers;

use App\Models\ChatHistory;
use App\Models\SuratTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session; // Tambahkan ini

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate(['message' => 'required|string']);
        
        // Identifikasi Pengirim
        $user = Auth::user();
        $userId = $user ? $user->id : null;
        $sessionId = Session::getId(); // Ambil ID browser untuk Tamu

        $pesanUser = $request->message;

        try {
            // 1. Simpan Pesan User (Pakai Session ID juga)
            ChatHistory::create([
                'user_id'    => $userId,
                'session_id' => $sessionId,
                'role'       => 'user',
                'message'    => $pesanUser,
            ]);

            // 2. Ambil History (Cek berdasarkan User ID ATAU Session ID)
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

            // 3. Prompt Sistem
            $systemInstruction = $this->getSystemInstruction();

            // 4. Kirim ke API
            $apiKey = env('GEMINI_API_KEY');
            if (!$apiKey) throw new \Exception("API Key belum disetting");

            $model = "gemini-2.0-flash-lite"; // Gunakan model yang stabil

            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                    'contents' => $history,
                    'systemInstruction' => [
                        'parts' => [['text' => $systemInstruction]]
                    ]
                ]);

            if ($response->failed()) {
                Log::error('Gemini API Error: ' . $response->body());
                $aiReply = "Maaf, sistem sedang sibuk. Silakan coba lagi nanti.";
            } else {
                $data = $response->json();
                $aiReply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, saya tidak mengerti.';
            }

        } catch (\Exception $e) {
            Log::error("Chat Controller Error: " . $e->getMessage());
            $aiReply = "Terjadi kesalahan sistem internal.";
        }

        // 5. Simpan Jawaban AI
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
        $daftarSurat = SuratTemplate::where('is_active', true)
            ->pluck('judul_surat')
            ->implode(', ');

        return "Anda adalah 'SiDesa', asisten virtual Desa Smart Digital.
        Konteks: Layanan Surat tersedia: {$daftarSurat}. Jam Kerja: 08.00-15.00.
        Jawablah dengan sopan dan singkat dalam Bahasa Indonesia.";
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