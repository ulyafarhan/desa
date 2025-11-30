<?php

namespace App\Http\Controllers;

use App\Models\ChatHistory;
use App\Models\SuratTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    // Mengirim pesan ke AI
    public function sendMessage(Request $request)
    {
        $request->validate(['message' => 'required|string']);
        $user = Auth::user();
        $pesanUser = $request->message;

        // 1. Simpan Pesan User ke Database
        ChatHistory::create([
            'user_id' => $user->id,
            'role' => 'user',
            'message' => $pesanUser,
        ]);

        // 2. Siapkan Konteks (Pengetahuan Dasar AI)
        // Kita ambil daftar surat agar AI tahu layanan apa yang tersedia
        $daftarSurat = SuratTemplate::where('is_active', true)->pluck('judul_surat')->implode(', ');
        
        $systemInstruction = "Anda adalah 'SiDesa', asisten virtual Desa Smart Digital yang ramah dan membantu. 
        Tugas Anda adalah membantu warga terkait administrasi desa.
        
        Pengetahuan Anda:
        - Nama Desa: Desa Smart Digital, Kec. Maju Jaya.
        - Layanan Surat yang tersedia saat ini: {$daftarSurat}.
        - Jam operasional kantor: Senin-Jumat, 08.00 - 15.00.
        
        Aturan Menjawab:
        - Jawab dengan singkat, padat, dan sopan.
        - Jika warga bertanya cara buat surat, arahkan mereka ke menu 'Dashboard'.
        - Jangan menjawab hal di luar konteks desa.
        - Gunakan Bahasa Indonesia yang baik.";

        // 3. Ambil 10 Riwayat Terakhir (Short-term Memory)
        $history = ChatHistory::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get()
            ->reverse() // Urutkan dari yang terlama ke terbaru
            ->map(function ($chat) {
                return [
                    'role' => $chat->role === 'user' ? 'user' : 'model',
                    'parts' => [['text' => $chat->message]]
                ];
            })
            ->values()
            ->toArray();

        // Tambahkan pesan baru ke history untuk dikirim ke API
        $history[] = [
            'role' => 'user',
            'parts' => [['text' => $pesanUser]]
        ];

        try {
            // 4. Kirim ke Google Gemini API
            $apiKey = env('GEMINI_API_KEY');
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                    'contents' => $history,
                    'systemInstruction' => [
                        'parts' => [['text' => $systemInstruction]]
                    ]
                ]);

            $aiResponseText = "Maaf, saya sedang gangguan.";
            
            if ($response->successful()) {
                $data = $response->json();
                $aiResponseText = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, saya tidak mengerti.';
            } else {
                Log::error('Gemini Error: ' . $response->body());
            }

            // 5. Simpan Jawaban AI
            ChatHistory::create([
                'user_id' => $user->id,
                'role' => 'model',
                'message' => $aiResponseText,
            ]);

            return response()->json(['reply' => $aiResponseText]);

        } catch (\Exception $e) {
            return response()->json(['reply' => 'Terjadi kesalahan sistem.'], 500);
        }
    }

    // Mengambil riwayat saat widget dibuka
    public function getHistory()
    {
        return ChatHistory::where('user_id', Auth::id())
            ->latest()
            ->take(20) // Ambil 20 pesan terakhir
            ->get()
            ->reverse()
            ->values();
    }
}