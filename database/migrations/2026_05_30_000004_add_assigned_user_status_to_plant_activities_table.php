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
        Schema::table('plant_activities', function (Blueprint $table) {
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete()->after('plant_id');
            $table->enum('status', ['belum_dikerjakan', 'sedang_dikerjakan', 'selesai', 'tidak_dilakukan'])->default('belum_dikerjakan')->after('description');
            $table->text('notes')->nullable()->after('done_at'); // catatan pelaksanaan/alasan
            $table->text('system_notes')->nullable()->after('notes'); // catatan dari sistem (kondisi cuaca, dll)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plant_activities', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['assigned_user_id']);
            $table->dropColumn(['assigned_user_id', 'status', 'notes', 'system_notes']);
        });
    }
};
