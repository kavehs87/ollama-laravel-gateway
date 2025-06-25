<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TokenUsage extends Model
{
    protected $fillable = [
        'user_id', 'model', 'operation',
        'prompt_tokens', 'completion_tokens',
        'total_tokens', 'cost_usd'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}