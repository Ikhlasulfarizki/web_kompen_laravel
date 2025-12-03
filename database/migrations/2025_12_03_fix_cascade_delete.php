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
        // Disable foreign key checks sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            // Drop foreign key di kelas
            Schema::table('kelas', function (Blueprint $table) {
                $table->dropForeign(['id_prodi']);
            });
        } catch (\Exception $e) {
            // Ignore if not exists
        }

        try {
            // Drop foreign key di prodi
            Schema::table('prodi', function (Blueprint $table) {
                $table->dropForeign(['id_jurusan']);
            });
        } catch (\Exception $e) {
            // Ignore if not exists
        }

        try {
            // Drop foreign key di mahasiswa
            Schema::table('mahasiswa', function (Blueprint $table) {
                $table->dropForeign(['id_kelas']);
            });
        } catch (\Exception $e) {
            // Ignore if not exists
        }

        try {
            // Drop foreign key di dosen
            Schema::table('dosen', function (Blueprint $table) {
                $table->dropForeign(['id_prodi']);
            });
        } catch (\Exception $e) {
            // Ignore if not exists
        }

        // Recreate dengan onDelete cascade
        Schema::table('prodi', function (Blueprint $table) {
            $table->foreign('id_jurusan')->references('id')->on('jurusan')->onDelete('cascade');
        });

        Schema::table('kelas', function (Blueprint $table) {
            $table->foreign('id_prodi')->references('id')->on('prodi')->onDelete('cascade');
        });

        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->foreign('id_kelas')->references('id')->on('kelas')->onDelete('cascade');
        });

        Schema::table('dosen', function (Blueprint $table) {
            $table->foreign('id_prodi')->references('id')->on('prodi')->onDelete('cascade');
        });

        // Enable foreign key checks kembali
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            Schema::table('kelas', function (Blueprint $table) {
                $table->dropForeign(['id_prodi']);
            });
        } catch (\Exception $e) {
            // Ignore
        }

        try {
            Schema::table('prodi', function (Blueprint $table) {
                $table->dropForeign(['id_jurusan']);
            });
        } catch (\Exception $e) {
            // Ignore
        }

        try {
            Schema::table('mahasiswa', function (Blueprint $table) {
                $table->dropForeign(['id_kelas']);
            });
        } catch (\Exception $e) {
            // Ignore
        }

        try {
            Schema::table('dosen', function (Blueprint $table) {
                $table->dropForeign(['id_prodi']);
            });
        } catch (\Exception $e) {
            // Ignore
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
