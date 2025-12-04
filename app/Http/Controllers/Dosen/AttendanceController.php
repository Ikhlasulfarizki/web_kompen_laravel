<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Participant;
use App\Models\Task;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Show attendance for a task
     */
    public function index(Task $task)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if ($task->id_dosen !== $dosen->id) {
            return abort(403, 'Anda tidak memiliki akses ke task ini');
        }

        $task->load(['participants.mahasiswa', 'participants.attendance']);

        return view('dosen.attendance.index', compact('task', 'dosen'));
    }

    /**
     * Record attendance check-in
     */
    public function checkIn(Request $request, Participant $participant)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();
        $task = $participant->task;

        if ($task->id_dosen !== $dosen->id) {
            return abort(403, 'Anda tidak memiliki akses ke task ini');
        }

        // Check if already checked in today
        $existing = Attendance::where('id_participant', $participant->id)
            ->whereDate('waktu_masuk', today())
            ->whereNull('waktu_keluar')
            ->first();

        if ($existing) {
            return back()->with('error', 'Peserta sudah check-in hari ini');
        }

        Attendance::create([
            'id_participant' => $participant->id,
            'waktu_masuk' => now(),
        ]);

        return back()->with('success', 'Check-in berhasil');
    }

    /**
     * Record attendance check-out
     */
    public function checkOut(Request $request, Attendance $attendance)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();
        $task = $attendance->participant->task;

        if ($task->id_dosen !== $dosen->id) {
            return abort(403, 'Anda tidak memiliki akses');
        }

        if ($attendance->waktu_keluar) {
            return back()->with('error', 'Peserta sudah check-out');
        }

        $waktu_keluar = now();
        $durasi_jam = $waktu_keluar->diffInMinutes($attendance->waktu_masuk) / 60;

        $attendance->update([
            'waktu_keluar' => $waktu_keluar,
            'durasi_jam' => round($durasi_jam, 2),
        ]);

        return back()->with('success', 'Check-out berhasil. Durasi: ' . number_format($durasi_jam, 2) . ' jam');
    }

    /**
     * Show attendance report for a task
     */
    public function report(Task $task)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if ($task->id_dosen !== $dosen->id) {
            return abort(403, 'Anda tidak memiliki akses ke task ini');
        }

        $participants = $task->participants()
            ->with('mahasiswa', 'attendance')
            ->get();

        $statistics = [];
        foreach ($participants as $participant) {
            $total_attendance = $participant->attendance->count();
            $total_duration = $participant->attendance->sum('durasi_jam');
            $statistics[$participant->id] = [
                'total_attendance' => $total_attendance,
                'total_duration' => round($total_duration, 2),
            ];
        }

        return view('dosen.attendance.report', compact('task', 'participants', 'statistics', 'dosen'));
    }

    /**
     * Delete attendance record
     */
    public function delete(Attendance $attendance)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();
        $task = $attendance->participant->task;

        if ($task->id_dosen !== $dosen->id) {
            return abort(403, 'Anda tidak memiliki akses');
        }

        $attendance->delete();

        return back()->with('success', 'Catatan absensi berhasil dihapus');
    }
}
