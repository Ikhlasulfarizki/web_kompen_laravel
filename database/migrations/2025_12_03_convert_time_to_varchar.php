<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE tasks MODIFY COLUMN jam_mulai VARCHAR(10)");
        DB::statement("ALTER TABLE tasks MODIFY COLUMN jam_selesai VARCHAR(10)");
        
        // Update existing data: extract only HH:mm from HH:mm:ss
        DB::statement("UPDATE tasks SET jam_mulai = SUBSTR(jam_mulai, 1, 5) WHERE jam_mulai IS NOT NULL");
        DB::statement("UPDATE tasks SET jam_selesai = SUBSTR(jam_selesai, 1, 5) WHERE jam_selesai IS NOT NULL");
        
        DB::statement("ALTER TABLE tasks MODIFY COLUMN jam_mulai VARCHAR(5)");
        DB::statement("ALTER TABLE tasks MODIFY COLUMN jam_selesai VARCHAR(5)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE tasks MODIFY COLUMN jam_mulai TIME");
        DB::statement("ALTER TABLE tasks MODIFY COLUMN jam_selesai TIME");
    }
};
