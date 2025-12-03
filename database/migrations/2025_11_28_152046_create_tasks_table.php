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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string("judul", 50)->nullable();
            $table->longText("deskripsi")->nullable();
            $table->string("lokasi", 50)->nullable();
            $table->dateTime("tanggal_waktu")->nullable();
            $table->integer("kuota")->nullable();
            $table->string("jam_mulai")->nullable();
            $table->string("jam_selesai")->nullable();
            $table->integer("jmlh_jam")->nullable();
            $table->foreignId("id_dosen")->nullable()->constrained('dosen')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId("id_task")->nullable()->constrained('tasks')->onDelete('cascade');
            $table->foreignId("id_mhs")->nullable()->constrained('mahasiswa')->onDelete('cascade');
            $table->string("status_pendaftaran", 50)->nullable();
            $table->longText("status_penyelesaian")->nullable();
            $table->string("status_acc", 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partisipants');
        Schema::dropIfExists('tasks');
    }
};
