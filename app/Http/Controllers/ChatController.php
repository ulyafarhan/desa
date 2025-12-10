<?php

namespace App\Http\Controllers;

use App\Models\ChatHistory;
use App\Models\SuratTemplate;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ChatController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function sendMessage(Request $request)
    {
        $request->validate(['message' => 'required|string|max:1000']);
        
        $user = Auth::user();
        $userId = $user ? $user->id : null;
        $sessionId = Session::getId();
        $pesanUser = $request->message;

        // 1. Simpan Pesan User ke DB
        ChatHistory::create([
            'user_id'    => $userId,
            'session_id' => $sessionId,
            'role'       => 'user',
            'message'    => $pesanUser,
        ]);

        // 2. Ambil History (10 pesan terakhir)
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
            ->sortBy('created_at') // Urutkan dari lama ke baru (Kronologis)
            ->map(function ($chat) {
                return [
                    'role' => $chat->role, 
                    'message' => $chat->message
                ];
            })
            ->values()
            ->toArray();

        // Hapus pesan terakhir (current message) dari array history agar tidak double
        // karena Service kita akan menambahkannya sendiri sebagai 'newMessage'
        array_pop($historyData);

        // 3. Panggil Service Gemini
        $systemInstruction = $this->getSystemInstruction();
        
        // Panggil method chat dengan 3 parameter
        $aiReply = $this->geminiService->chat($historyData, $pesanUser, $systemInstruction);

        // 4. Simpan Jawaban AI ke DB
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
        // Ambil data surat untuk konteks
        $daftarSurat = SuratTemplate::where('is_active', true)
            ->pluck('judul_surat')
            ->implode(', ');

        return "Kamu adalah 'SiDesa', asisten virtual Desa Smart Digital.
        
        Konteks Desa:
        - Layanan Surat Tersedia: {$daftarSurat}.
        - Jam Kerja: Senin-Jumat, 08.00 - 15.00 WIB.
        
        Instruksi:
        - Jawablah pertanyaan warga dengan bahasa Indonesia yang sopan dan membantu.
        - Jika ditanya tentang surat, sebutkan surat yang tersedia di atas.
        - Jawaban harus ringkas (maksimal 2-3 paragraf pendek).";
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