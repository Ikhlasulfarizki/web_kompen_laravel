<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teknisi extends Model
{
    protected $table = "teknisi";

    protected $fillable = [
        "user_id",
        "nip",
        "tgl_lahir",
        "nama",
        "jenis_kelamin",
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
