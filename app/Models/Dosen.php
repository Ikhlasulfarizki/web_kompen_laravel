<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $table = "dosen";
    
    protected $fillable = [
        "user_id",
        "nip",
        "tgl_lahir",
        "nama",
        "jenis_kelamin",
        "id_prodi"
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Prodi
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi');
    }

    // Relasi ke Tasks (satu dosen bisa punya banyak tasks)
    public function tasks()
    {
        return $this->hasMany(Task::class, 'id_dosen');
    }
}
