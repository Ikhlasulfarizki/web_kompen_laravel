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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('nama_role')->nullable();
            $table->timestamps();
        });

        Schema::create('jurusan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jurusan')->nullable();
            $table->timestamps();
        });
        
        Schema::create('prodi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_jurusan')->constrained('jurusan');;
            $table->string('nama_prodi')->nullable();
            $table->timestamps();
        });
        
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas')->nullable(); 
            $table->foreignId('id_prodi')->constrained('prodi');;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
        Schema::dropIfExists('prodi');
        Schema::dropIfExists('jurusan');
        Schema::dropIfExists('roles');
    }
};
