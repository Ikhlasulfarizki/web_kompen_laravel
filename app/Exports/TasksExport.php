<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Task;

class TasksExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $dosenId;

    public function __construct($dosenId)
    {
        $this->dosenId = $dosenId;
    }

    public function query()
    {
        return Task::where('id_dosen', $this->dosenId)
            ->with('participants')
            ->orderBy('tanggal_waktu', 'desc');
    }

    public function headings(): array
    {
        return [
            'No',
            'Judul',
            'Lokasi',
            'Tanggal',
            'Durasi',
            'Kuota',
            'Peserta Terdaftar',
            'Peserta Diterima',
            'Peserta Ditolak',
            'Peserta Selesai',
        ];
    }

    public function map($task): array
    {
        static $count = 0;
        $count++;

        $accepted = $task->participants->where('status_acc', 'Diterima')->count();
        $rejected = $task->participants->where('status_acc', 'Ditolak')->count();
        $completed = $task->participants->where('status_penyelesaian', 'Selesai')->count();

        return [
            $count,
            $task->judul,
            $task->lokasi,
            $task->tanggal_waktu->format('d-m-Y H:i'),
            formatJam($task->jmlh_jam),
            $task->kuota,
            $task->participants->count(),
            $accepted,
            $rejected,
            $completed,
        ];
    }
}
