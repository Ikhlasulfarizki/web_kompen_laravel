<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendance';

    protected $fillable = [
        'id_participant',
        'waktu_masuk',
        'waktu_keluar',
        'durasi_jam',
        'catatan',
    ];

    protected $casts = [
        'waktu_masuk' => 'datetime',
        'waktu_keluar' => 'datetime',
    ];

    public function participant()
    {
        return $this->belongsTo(Participant::class, 'id_participant');
    }

    public function mahasiswa()
    {
        return $this->hasOneThrough(
            Mahasiswa::class,
            Participant::class,
            'id',
            'id',
            'id_participant',
            'id_mahasiswa'
        );
    }
}
