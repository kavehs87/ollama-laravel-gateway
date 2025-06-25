<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelPrice extends Model
{
    protected $fillable = ['model', 'price_per_1k'];

    public static function getRateFor($model)
    {
        return self::where('model', $model)->value('price_per_1k') ?? 0.002; // default
    }
}