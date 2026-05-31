<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('plants', function (Blueprint $table) {
            if (!Schema::hasColumn('plants', 'owner_id')) {
                $table->unsignedBigInteger('owner_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('plants', 'variety')) {
                $table->string('variety')
                    ->default('Cabai Rawit')
                    ->after('name');
            }
            if (Schema::hasColumn('plants', 'start_date') && !Schema::hasColumn('plants', 'plant_date')) {
                $table->renameColumn('start_date', 'plant_date');
            }
            if (Schema::hasColumn('plants', 'location')) {
                $table->dropColumn('location');
            }
            if (Schema::hasColumn('plants', 'harvest_date')) {
                $table->dropColumn('harvest_date');
            }
        });

        // Set owner_id to 1 for all existing records
        DB::table('plants')->whereNull('owner_id')->update(['owner_id' => 1]);
    }

    public function down(): void
    {
        Schema::table('plants', function (Blueprint $table) {
            if (Schema::hasColumn('plants', 'owner_id')) {
                try {
                    $table->dropForeign(['owner_id']);
                } catch (\Exception $e) {
                    // Foreign key might not exist
                }
                $table->dropColumn('owner_id');
            }
        });
    }
};



