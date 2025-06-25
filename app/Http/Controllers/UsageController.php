<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsageController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $usage = $user->tokenUsages()
            ->whereMonth('created_at', now()->month)
            ->selectRaw('SUM(prompt_tokens) as prompt_tokens, SUM(completion_tokens) as completion_tokens, SUM(total_tokens) as total_tokens, SUM(cost_usd) as cost')
            ->first();

        return response()->json([
            'month' => now()->format('Y-m'),
            'prompt_tokens' => $usage->prompt_tokens ?? 0,
            'completion_tokens' => $usage->completion_tokens ?? 0,
            'total_tokens' => $usage->total_tokens ?? 0,
            'billed_amount_usd' => number_format($usage->cost ?? 0, 4),
            'pricing' => ModelPrice::pluck('price_per_1k', 'model'),
        ]);
    }

    public function history(Request $request)
    {
        $user = $request->user();

        $history = $user->tokenUsages()
            ->selectRaw('DATE(created_at) as date, SUM(total_tokens) as tokens, SUM(cost_usd) as cost')
            ->groupByRaw('DATE(created_at)')
            ->orderByDesc('date')
            ->get();

        return response()->json($history);
    }
}
