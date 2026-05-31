<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plant_id')->constrained('plants')->onDelete('cascade');
            $table->foreignId('penyuluh_id')->constrained('users')->onDelete('cascade');
            $table->text('recommendation_text');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommendations');
    }
};
