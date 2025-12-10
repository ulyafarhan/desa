<?php

namespace App\Services;

use Gemini\Laravel\Facades\Gemini;
use Gemini\Enums\Role;
use Gemini\Data\Content;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    public function chat(array $historyData, string $userMessage, string $systemInstruction): string
    {
        try {
            $history = [];
            foreach ($historyData as $chat) {
                $role = $chat['role'] === 'user' ? Role::USER : Role::MODEL;
                $history[] = Content::parse(part: $chat['message'], role: $role);
            }

            $chatSession = Gemini::generativeModel(model: 'gemini-1.5-flash')
                ->withSystemInstruction(Content::parse($systemInstruction)) // Fix: String harus dibungkus Content::parse()
                ->startChat(history: $history);

            $response = $chatSession->sendMessage($userMessage);

            return $response->text();

        } catch (\Exception $e) {
            Log::error("Gemini Error: " . $e->getMessage());
            return "Maaf, terjadi kesalahan saat menghubungi AI. Silakan coba lagi nanti.";
        }
    }
}