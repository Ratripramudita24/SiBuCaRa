<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['owner', 'worker', 'penyuluh'])
                    ->default('owner')
                    ->after('password');
            }
            if (!Schema::hasColumn('users', 'owner_id')) {
                $table->foreignId('owner_id')
                    ->nullable()
                    ->constrained('users')
                    ->onDelete('cascade')
                    ->after('role');
            }
            if (Schema::hasColumn('users', 'email_verified_at')) {
                $table->dropColumn('email_verified_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'owner_id')) {
                $table->dropForeign(['owner_id']);
                $table->dropColumn('owner_id');
            }
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
            $table->timestamp('email_verified_at')->nullable();
        });
    }
};
