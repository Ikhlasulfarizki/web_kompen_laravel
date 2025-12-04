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
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_participant')
                ->constrained('participants')
                ->cascadeOnDelete();
            $table->timestamp('waktu_masuk');
            $table->timestamp('waktu_keluar')->nullable();
            $table->decimal('durasi_jam', 5, 2)->nullable(); // Durasi dalam jam
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Index untuk query lebih cepat
            $table->index('id_participant');
            $table->index('waktu_masuk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
