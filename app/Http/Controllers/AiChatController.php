<?php

namespace App\Http\Controllers;

use App\Models\AiChat;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AiChatController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    // Mengambil history chat
    public function index()
    {
        $userId = Auth::id();
        
        $chats = AiChat::where('user_id', $userId)
            ->orderBy('created_at', 'asc')
            ->get(['role', 'content']);

        return response()->json($chats);
    }

    // Mengirim pesan
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $userId = Auth::id();
        $message = $request->input('message');

        // 1. Simpan pesan user
        AiChat::create([
            'user_id' => $userId,
            'role' => 'user',
            'content' => $message,
        ]);

        // 2. Ambil history chat terakhir (limit 10 agar tidak terlalu berat)
        $history = AiChat::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->reverse()
            ->toArray();

        // 3. Panggil API Gemini
        $aiResponseText = $this->geminiService->chat($history, $message);

        // 4. Simpan balasan AI
        $chatResponse = AiChat::create([
            'user_id' => $userId,
            'role' => 'model',
            'content' => $aiResponseText,
        ]);

        return response()->json([
            'role' => 'model',
            'content' => $aiResponseText
        ]);
    }
    
    // Clear chat
    public function destroy()
    {
        AiChat::where('user_id', Auth::id())->delete();
        return response()->json(['message' => 'Chat history cleared']);
    }
}