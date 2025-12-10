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

        // 2. Ambil History untuk konteks (Oldest to Newest)
        $historyData = ChatHistory::query()
            ->where(function($q) use ($userId, $sessionId) {
                if ($userId) {
                    $q->where('user_id', $userId);
                } else {
                    $q->where('session_id', $sessionId);
                }
            })
            ->latest()
            ->take(10) // Ambil 10 pesan terakhir
            ->get()
            ->reverse() // Balik agar urutannya kronologis (Lama -> Baru)
            ->map(function ($chat) {
                return [
                    'role' => $chat->role, 
                    'message' => $chat->message
                ];
            })
            ->values()
            ->toArray();

        // Hapus pesan terakhir (current message) dari history agar tidak duplikat saat dikirim ke startChat
        // karena sendMessage() di service akan mengirim pesan ini secara terpisah.
        array_pop($historyData);

        // 3. Panggil Service Gemini
        $systemInstruction = $this->getSystemInstruction();
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
        $daftarSurat = SuratTemplate::where('is_active', true)
            ->pluck('judul_surat')
            ->implode(', ');

        return "Kamu adalah 'SiDesa', asisten virtual Desa Smart Digital yang ramah.
        
        Data Desa:
        - Layanan Surat: {$daftarSurat}.
        - Jam Kerja: Senin-Jumat, 08.00 - 15.00 WIB.
        
        Instruksi:
        - Jawab sapaan dengan ramah.
        - Fokus menjawab pertanyaan seputar administrasi desa.
        - Gunakan Bahasa Indonesia yang sopan dan ringkas (maksimal 3 paragraf).";
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