<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('model_prices', function (Blueprint $table) {
            $table->id();
            $table->string('model')->unique(); // e.g., 'llama3', 'mistral:7b'
            $table->decimal('price_per_1k', 8, 4); // e.g., 0.0020
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_prices');
    }
};
