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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('password');
            $table->foreignId('role_id')->constrained('roles');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('npm', 45)->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('nama', 45)->nullable();
            $table->string('jenis_kelamin', 45)->nullable();
            $table->foreignId('id_kelas')->nullable()->constrained('kelas');
            $table->integer('jumlah_jam')->nullable();
        });

        Schema::create('dosen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');;
            $table->string('nip', 45)->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('nama', 45)->nullable();
            $table->string('jenis_kelamin', 45)->nullable();
            $table->foreignId('id_prodi')->nullable()->constrained('prodi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('mahasiswa');
        Schema::dropIfExists('dosen');
    }
};
