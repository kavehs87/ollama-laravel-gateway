<?php

namespace App\Http\Middleware;

use App\Models\ModelPrice;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackTokenUsage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $user = $request->user();
        $route = $request->route()->getName();

        if (!$user || !in_array($route, ['generate', 'chat', 'embedding'])) return $response;

        $data = $response->getData(true);
        $promptTokens = $data['usage']['prompt_tokens'] ?? 0;
        $completionTokens = $data['usage']['completion_tokens'] ?? 0;
        $totalTokens = $promptTokens + $completionTokens;

        $pricePer1K = 0.002; // adjust per model if needed
        $cost = ($totalTokens / 1000) * $pricePer1K;

        $rate = ModelPrice::getRateFor($request->input('model'));
        $cost = ($totalTokens / 1000) * $rate;

        \App\Models\TokenUsage::create([
            'user_id' => $user->id,
            'model' => $request->input('model'),
            'operation' => $route,
            'prompt_tokens' => $promptTokens,
            'completion_tokens' => $completionTokens,
            'total_tokens' => $totalTokens,
            'cost_usd' => round($cost, 4)
        ]);

        return $response;
    }
}
