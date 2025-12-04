<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = "tasks";

    protected $fillable = [
        "judul",
        "deskripsi",
        "lokasi",
        "tanggal_waktu",
        "kuota",
        "jam_mulai",
        "jam_selesai",
        "jmlh_jam",
        "id_dosen"
    ];

    protected $casts = [
        'tanggal_waktu' => 'datetime',
    ];

    // Relasi ke Dosen
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen');
    }

    // Relasi ke Participants dengan cascade delete
    public function participants()
    {
        return $this->hasMany(Participant::class, 'id_task');
    }

    /**
     * The "booting" method of the model.
     * Automatically delete participants ketika task dihapus
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($task) {
            // Delete all participants associated with this task
            $task->participants()->delete();
        });
    }
}

