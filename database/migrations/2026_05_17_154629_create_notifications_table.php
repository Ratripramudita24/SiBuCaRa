<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plant_activity_id')->constrained()->cascadeOnDelete();

            $table->string('channel');
            $table->text('message');

            $table->dateTime('scheduled_at');
            $table->dateTime('sent_at')->nullable();

            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
