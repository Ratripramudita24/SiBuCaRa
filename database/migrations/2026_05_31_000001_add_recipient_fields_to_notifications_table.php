<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('recipient_role')->nullable()->after('status');
            $table->foreignId('recipient_user_id')->nullable()->after('recipient_role')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['recipient_user_id']);
            $table->dropColumn(['recipient_role', 'recipient_user_id']);
        });
    }
};
