<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $table = "participants";
    
    protected $fillable = [
        "id_task",
        "id_mhs",
        "status_pendaftaran",
        "status_penyelesaian",
        "status_acc"
    ];

    // Relasi ke Task
    public function task()
    {
        return $this->belongsTo(Task::class, 'id_task');
    }

    // Relasi ke Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mhs');
    }
}
