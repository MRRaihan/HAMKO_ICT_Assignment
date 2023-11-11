<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatInteraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatGPTController extends Controller
{
    public function interactWithChatGPT(Request $request)
    {

        $apiKey = config('services.chatgpt.api_key');


        $response = Http::post('https://api.openai.com/v1/chat/completions', [
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                ['role' => 'user', 'content' => $request->input('user_message')],
            ],
        ])->withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ]);

        // Handle the response
        $result = $response->json();

        // Save the result to the database with the authenticated user
        $interaction = new ChatInteraction();
        $interaction->user_id = auth()->id();
        $interaction->user_message = $request->input('user_message');
        $interaction->chatgpt_response = json_encode($result);
        $interaction->save();

        return response()->json($result);
    }
}
