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
        Schema::table("mahasiswa", function (Blueprint $table){
            if (!Schema::hasColumn('mahasiswa', 'created_at')) {
                $table->timestamp("created_at")->nullable();
            }
            if (!Schema::hasColumn('mahasiswa', 'updated_at')) {
                $table->timestamp("updated_at")->nullable();
            }
        });

        Schema::table("dosen", function (Blueprint $table){
            if (!Schema::hasColumn('dosen', 'created_at')) {
                $table->timestamp("created_at")->nullable();
            }
            if (!Schema::hasColumn('dosen', 'updated_at')) {
                $table->timestamp("updated_at")->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
