<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plant_id')->constrained('plants')->onDelete('cascade');
            $table->string('activity_name');
            $table->date('target_date');
            $table->enum('status', ['belum dikerjakan', 'sedang dikerjakan', 'selesai', 'tidak dilakukan'])
                ->default('belum dikerjakan');
            $table->text('notes')->nullable();
            $table->text('reason_not_done')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
