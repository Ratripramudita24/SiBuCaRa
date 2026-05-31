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
        Schema::table('plants', function (Blueprint $table) {
            $table->foreignId('owner_id')->nullable()->constrained('users')->cascadeOnDelete()->after('id');
            $table->foreignId('variety_id')->nullable()->constrained('varieties')->cascadeOnDelete()->after('owner_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plants', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['owner_id', 'variety_id']);
            $table->dropColumn(['owner_id', 'variety_id']);
        });
    }
};
