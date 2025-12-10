<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $apiKey;
    protected $baseUrl;
    protected $model;

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
        $this->model = env('GEMINI_MODEL', 'gemini-2.5-flash-lite');
        $this->baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/' . $this->model . ':generateContent';
    }

    public function chat(array $history, string $newMessage, string $systemInstruction = '')
    {
        $contents = [];

        // 1. System Instruction (Workaround)
        if (!empty($systemInstruction)) {
            $contents[] = [
                'role' => 'user',
                'parts' => [['text' => "SYSTEM INSTRUCTION:\n" . $systemInstruction]]
            ];
            $contents[] = [
                'role' => 'model',
                'parts' => [['text' => "Dimengerti."]]
            ];
        }

        // 2. History
        foreach ($history as $chat) {
            $role = ($chat['role'] === 'user') ? 'user' : 'model';
            $text = $chat['message'] ?? $chat['content'] ?? '';
            $contents[] = [
                'role' => $role,
                'parts' => [['text' => $text]]
            ];
        }

        // 3. New Message
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $newMessage]]
        ];

        try {
            // PERBAIKAN: Tambahkan verify => false untuk mengatasi masalah SSL Laragon
            $response = Http::withOptions(['verify' => false])
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($this->baseUrl . '?key=' . $this->apiKey, [
                    'contents' => $contents,
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'maxOutputTokens' => 1000,
                    ]
                ]);

            if ($response->failed()) {
                // DEBUG: Tampilkan error langsung ke layar agar terlihat di Tinker
                $errorBody = $response->json();
                $errorMessage = $errorBody['error']['message'] ?? $response->body();
                
                echo "\n\n[ERROR DARI GOOGLE]: " . $errorMessage . "\n\n";
                
                Log::error('Gemini API Error: ' . $response->body());
                return 'Maaf, sistem AI sedang sibuk (API Error).';
            }

            $data = $response->json();
            return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, tidak ada respon.';

        } catch (\Exception $e) {
            echo "\n\n[ERROR KONEKSI]: " . $e->getMessage() . "\n\n";
            Log::error('Gemini Service Exception: ' . $e->getMessage());
            return 'Terjadi kesalahan koneksi server.';
        }
    }
}