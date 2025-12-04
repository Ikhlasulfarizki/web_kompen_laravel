<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Participant;
use App\Models\Dosen;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dosen dashboard with overview grid
     */
    public function index()
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            return abort(403, 'Dosen tidak ditemukan');
        }

        // Total Tasks
        $totalTasks = Task::where('id_dosen', $dosen->id)->count();

        // Pending Participants (waiting for acceptance)
        $pendingParticipants = Participant::whereHas('task', function ($query) use ($dosen) {
            $query->where('id_dosen', $dosen->id);
        })
        ->whereNull('status_acc')
        ->with(['mahasiswa.kelas.prodi.jurusan', 'task'])
        ->count();

        // Completed Tasks (status_penyelesaian = 'Selesai')
        $completedTasks = Participant::whereHas('task', function ($query) use ($dosen) {
            $query->where('id_dosen', $dosen->id);
        })
        ->where('status_penyelesaian', 'Selesai')
        ->with(['mahasiswa.kelas.prodi.jurusan', 'task'])
        ->count();

        // Get data untuk display
        // Recent pending participants
        $recentPending = Participant::whereHas('task', function ($query) use ($dosen) {
            $query->where('id_dosen', $dosen->id);
        })
        ->whereNull('status_acc')
        ->with(['mahasiswa.kelas.prodi.jurusan', 'task'])
        ->latest('created_at')
        ->limit(5)
        ->get();

        // Recent tasks (upcoming)
        $upcomingTasks = Task::where('id_dosen', $dosen->id)
            ->where('tanggal_waktu', '>=', now())
            ->with('participants')
            ->orderBy('tanggal_waktu')
            ->limit(5)
            ->get();

        // Recent completed (history)
        $completedHistory = Participant::whereHas('task', function ($query) use ($dosen) {
            $query->where('id_dosen', $dosen->id);
        })
        ->where('status_penyelesaian', 'Selesai')
        ->with(['mahasiswa.kelas.prodi.jurusan', 'task'])
        ->latest('updated_at')
        ->limit(5)
        ->get();

        return view('dosen.dashboard', compact(
            'dosen',
            'totalTasks',
            'pendingParticipants',
            'completedTasks',
            'recentPending',
            'upcomingTasks',
            'completedHistory'
        ));
    }
}
