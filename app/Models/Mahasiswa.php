<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = "mahasiswa";
    protected $fillable = [
        "npm",
        "tgl_lahir",
        "nama",
        "jenis_kelamin",
        "id_kelas",
        "jumlah_jam"
    ];

    // Ngedeklarasiin relasi bahwa di tb-mahasiswa ini make foreignKey dari id_kelas dari tb-kelas
    // biar bisa make eloquent
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

}
