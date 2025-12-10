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
    public function sendMessage(Request $request)
    {
        $request->validate(['message' => 'required|string']);
        
        $user = Auth::user();
        $pesanUser = $request->message;

        try {
            // 1. Simpan Pesan User
            ChatHistory::create([
                'user_id' => $user->id,
                'role'    => 'user',
                'message' => $pesanUser,
            ]);

            // 2. Ambil History & Pastikan Format String Aman
            // Google Gemini sangat sensitif jika ada nilai null
            $historyData = ChatHistory::where('user_id', $user->id)
                ->latest()
                ->take(10)
                ->get()
                ->reverse();

            $history = [];
            foreach ($historyData as $chat) {
                $history[] = [
                    'role' => $chat->role === 'user' ? 'user' : 'model',
                    'parts' => [['text' => (string) $chat->message]] // Paksa jadi string
                ];
            }

            // 3. Prompt Sistem
            $systemInstruction = $this->getSystemInstruction();

            // 4. Kirim ke API
            $apiKey = env('GEMINI_API_KEY');
            if (!$apiKey) {
                throw new \Exception("API Key belum disetting di .env");
            }

            $model = "gemini-2.0-flash-lite";

            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                    'contents' => $history,
                    'systemInstruction' => [
                        'parts' => [['text' => $systemInstruction]]
                    ]
                ]);

            // Cek jika API gagal
            if ($response->failed()) {
                Log::error('Gemini API Error: ' . $response->body());
                $aiReply = "Maaf, saya sedang pusing (Error Server). Coba lagi nanti.";
            } else {
                $data = $response->json();
                $aiReply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, saya tidak mengerti.';
            }

        } catch (\Exception $e) {
            Log::error("Chat Controller Error: " . $e->getMessage());
            $aiReply = "Terjadi kesalahan sistem internal.";
        }

        // 5. Simpan Jawaban AI (Apapun hasilnya, tetap simpan agar chat tidak macet)
        ChatHistory::create([
            'user_id' => $user->id,
            'role'    => 'model',
            'message' => $aiReply,
        ]);

        return response()->json(['reply' => $aiReply]);
    }

    // Fungsi khusus untuk menyusun instruksi agar Controller utama tidak berantakan
    private function getSystemInstruction()
    {
        // Cache query ini jika trafik tinggi, tapi untuk sekarang direct query oke
        $daftarSurat = SuratTemplate::where('is_active', true)
            ->pluck('judul_surat')
            ->implode(', ');

        return "Anda adalah 'SiDesa', asisten virtual Desa Smart Digital.
        
        Konteks Desa:
        - Nama: Desa Smart Digital, Kec. Maju Jaya.
        - Layanan Surat Tersedia: {$daftarSurat}.
        - Jam Kerja: Senin-Jumat, 08.00 - 15.00 WIB.

        Instruksi Penting:
        1. Jawablah dengan singkat, ramah, dan to the point.
        2. Jika warga bertanya cara membuat surat, arahkan ke menu 'Layanan Mandiri' atau 'Dashboard'.
        3. Gunakan Bahasa Indonesia yang baku namun luwes.
        4. Jangan menjawab pertanyaan di luar topik administrasi desa.";
    }

    public function getHistory()
    {
        return ChatHistory::where('user_id', Auth::id())
            ->latest()
            ->take(20)
            ->get()
            ->reverse()
            ->values();
    }
}