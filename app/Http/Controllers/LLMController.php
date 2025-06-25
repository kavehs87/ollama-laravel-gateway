<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class LLMController extends Controller
{
    public function generate(Request $request)
    {
        $response = Http::post('http://localhost:11434/api/generate', [
            'model' => $request->model,
            'prompt' => $request->prompt,
            'options' => ['log_usage' => true],
            'stream' => false
        ]);

        $data = $response->json();

        return response()->json([
            'response' => $data['response'] ?? '',
            'usage' => $data['usage'] ?? [
                'prompt_tokens' => $this->estimateTokens($request->prompt),
                'completion_tokens' => $this->estimateTokens($data['response'] ?? '')
            ]
        ]);
    }

    private function estimateTokens($text)
    {
        return round(str_word_count($text) * 1.3); // rough estimate
    }

    public function chat(Request $request)
    {
        $response = Http::post('http://localhost:11434/api/chat', [
            'model' => $request->model,
            'messages' => $request->messages,
            'stream' => false
        ]);

        $data = $response->json();
        return response()->json([
            'response' => $data['message']['content'] ?? '',
            'usage' => $data['usage'] ?? [
                'prompt_tokens' => $this->estimateTokens(json_encode($request->messages)),
                'completion_tokens' => $this->estimateTokens($data['message']['content'] ?? '')
            ]
        ]);
    }

    public function embedding(Request $request)
    {
        $response = Http::post('http://localhost:11434/api/embedding', [
            'model' => $request->model,
            'prompt' => $request->prompt,
        ]);

        return response()->json($response->json());
    }

    public function pullModel(Request $request)
    {
        $response = Http::post('http://localhost:11434/api/pull', [
            'name' => $request->model
        ]);
        return response()->json($response->json());
    }

    public function deleteModel($model)
    {
        $response = Http::delete("http://localhost:11434/api/models/$model");
        return response()->json($response->json());
    }
}
