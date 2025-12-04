<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Participant;
use App\Models\Dosen;
use App\Exports\TasksExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks for the logged-in dosen with search & filter
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            return abort(403, 'Dosen tidak ditemukan');
        }

        $query = Task::where('id_dosen', $dosen->id)
            ->with(['participants.mahasiswa.kelas.prodi.jurusan', 'dosen']);

        // Search by title or location
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('tanggal_waktu', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('tanggal_waktu', '<=', $request->input('date_to'));
        }

        // Filter by status (upcoming, past, all)
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'upcoming') {
                $query->where('tanggal_waktu', '>=', now());
            } elseif ($status === 'past') {
                $query->where('tanggal_waktu', '<', now());
            }
        }

        // Sort - with validation to prevent SQL injection
        $sort = $request->input('sort', 'tanggal_waktu');
        $direction = $request->input('direction', 'desc');

        // Whitelist allowed sort fields
        $allowedSortFields = ['tanggal_waktu', 'judul', 'created_at'];
        if (!in_array($sort, $allowedSortFields)) {
            $sort = 'tanggal_waktu';
        }

        // Validate direction
        $allowedDirections = ['asc', 'desc'];
        if (!in_array($direction, $allowedDirections)) {
            $direction = 'desc';
        }

        $query->orderBy($sort, $direction);

        $tasks = $query->paginate(10)->appends($request->query());

        return view('dosen.tasks.index', compact('tasks', 'dosen'));
    }

    /**
     * Show the form for creating a new task
     */
    public function create()
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            return abort(403, 'Dosen tidak ditemukan');
        }

        return view('dosen.tasks.create', compact('dosen'));
    }

    /**
     * Store a newly created task in storage
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            return abort(403, 'Dosen tidak ditemukan');
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'lokasi' => 'required|string|max:255',
            'tanggal_waktu' => 'required|date',
            'kuota' => 'required|integer|min:1',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
            'jam' => 'required|integer|min:0|max:24',
            'menit' => 'required|integer|min:0|max:59',
        ]);

        // Convert jam and menit to jmlh_jam (in minutes)
        $jam = (int) $validated['jam'];
        $menit = (int) $validated['menit'];
        $totalMinutes = ($jam * 60) + $menit;

        // Validate total duration is greater than 0
        if ($totalMinutes <= 0) {
            return back()->withInput()->withErrors(['durasi' => 'Durasi task harus lebih dari 0']);
        }

        // Prepare data for creation
        $data = [
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'lokasi' => $validated['lokasi'],
            'tanggal_waktu' => $validated['tanggal_waktu'],
            'kuota' => $validated['kuota'],
            'jam_mulai' => $validated['jam_mulai'],
            'jam_selesai' => $validated['jam_selesai'],
            'jmlh_jam' => $totalMinutes,
            'id_dosen' => $dosen->id,
        ];

        Task::create($data);

        return redirect()->route('dosen.tasks.index')
            ->with('success', 'Task berhasil dibuat');
    }

    /**
     * Display the specified task with its participants
     */
    public function show(Task $task)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        // Verifikasi task milik dosen ini
        if ($task->id_dosen !== $dosen->id) {
            return abort(403, 'Anda tidak memiliki akses ke task ini');
        }

        $task->load([
            'participants.mahasiswa.kelas.prodi.jurusan',
            'dosen'
        ]);

        $participants = $task->participants()->paginate(10);

        return view('dosen.tasks.show', compact('task', 'participants', 'dosen'));
    }

    /**
     * Show the form for editing the specified task
     */
    public function edit(Task $task)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        // Verifikasi task milik dosen ini
        if ($task->id_dosen !== $dosen->id) {
            return abort(403, 'Anda tidak memiliki akses ke task ini');
        }

        return view('dosen.tasks.edit', compact('task', 'dosen'));
    }

    /**
     * Update the specified task in storage
     */
    public function update(Request $request, Task $task)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        // Verifikasi task milik dosen ini
        if ($task->id_dosen !== $dosen->id) {
            return abort(403, 'Anda tidak memiliki akses ke task ini');
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'lokasi' => 'required|string|max:255',
            'tanggal_waktu' => 'required|date',
            'kuota' => 'required|integer|min:1',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
            'jam' => 'required|integer|min:0|max:24',
            'menit' => 'required|integer|min:0|max:59',
        ]);

        // Convert jam and menit to jmlh_jam (in minutes)
        $jam = (int) $validated['jam'];
        $menit = (int) $validated['menit'];
        $totalMinutes = ($jam * 60) + $menit;

        // Validate total duration is greater than 0
        if ($totalMinutes <= 0) {
            return back()->withInput()->withErrors(['durasi' => 'Durasi task harus lebih dari 0']);
        }

        // Prepare data for update
        $data = [
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'lokasi' => $validated['lokasi'],
            'tanggal_waktu' => $validated['tanggal_waktu'],
            'kuota' => $validated['kuota'],
            'jam_mulai' => $validated['jam_mulai'],
            'jam_selesai' => $validated['jam_selesai'],
            'jmlh_jam' => $totalMinutes,
        ];

        $task->update($data);

        return redirect()->route('dosen.tasks.show', $task->id)
            ->with('success', 'Task berhasil diperbarui');
    }

    /**
     * Delete the specified task
     * Automatically detach all participants
     */
    public function destroy(Task $task)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        // Verifikasi task milik dosen ini
        if ($task->id_dosen !== $dosen->id) {
            return abort(403, 'Anda tidak memiliki akses ke task ini');
        }

        // Hapus semua participant dari task ini (cascade delete otomatis dari database)
        Participant::where('id_task', $task->id)->delete();

        // Hapus task
        $task->delete();

        return redirect()->route('dosen.tasks.index')
            ->with('success', 'Task berhasil dihapus');
    }

    /**
     * Accept participant for a task
     */
    public function acceptParticipant(Request $request, Participant $participant)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();
        $task = $participant->task;

        // Verifikasi task milik dosen ini
        if ($task->id_dosen !== $dosen->id) {
            return abort(403, 'Anda tidak memiliki akses ke task ini');
        }

        $participant->update([
            'status_acc' => 'Diterima'
        ]);

        return back()->with('success', 'Partisipan berhasil diterima');
    }

    /**
     * Reject participant for a task
     */
    public function rejectParticipant(Request $request, Participant $participant)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();
        $task = $participant->task;

        // Verifikasi task milik dosen ini
        if ($task->id_dosen !== $dosen->id) {
            return abort(403, 'Anda tidak memiliki akses ke task ini');
        }

        $participant->update([
            'status_acc' => 'Ditolak'
        ]);

        return back()->with('success', 'Partisipan berhasil ditolak');
    }

    /**
     * Update participant completion status
     */
    public function updateParticipantStatus(Request $request, Participant $participant)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();
        $task = $participant->task;

        // Verifikasi task milik dosen ini
        if ($task->id_dosen !== $dosen->id) {
            return abort(403, 'Anda tidak memiliki akses ke task ini');
        }

        $validated = $request->validate([
            'status_penyelesaian' => 'required|in:Selesai,Tidak Selesai'
        ]);

        // Jika status diubah menjadi Selesai, tambahkan jam ke mahasiswa
        $oldStatus = $participant->status_penyelesaian;
        $participant->update($validated);

        if ($oldStatus !== 'Selesai' && $validated['status_penyelesaian'] === 'Selesai') {
            // Tambahkan jam ke mahasiswa
            $mahasiswa = $participant->mahasiswa;
            $mahasiswa->increment('jumlah_jam', $task->jmlh_jam);
        } elseif ($oldStatus === 'Selesai' && $validated['status_penyelesaian'] !== 'Selesai') {
            // Kurangi jam dari mahasiswa jika perubahan dari Selesai ke status lain
            $mahasiswa = $participant->mahasiswa;
            $mahasiswa->decrement('jumlah_jam', $task->jmlh_jam);
        }

        return back()->with('success', 'Status partisipan berhasil diperbarui');
    }

    /**
     * Export tasks to Excel
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            return abort(403, 'Dosen tidak ditemukan');
        }

        $filename = 'Tasks_' . $dosen->nama . '_' . date('Y-m-d_H-i-s') . '.xlsx';
        return Excel::download(new TasksExport($dosen->id), $filename);
    }

    /**
     * Bulk delete tasks
     */
    public function bulkDelete(Request $request)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            return abort(403, 'Dosen tidak ditemukan');
        }

        $validated = $request->validate([
            'task_ids' => 'required|array',
            'task_ids.*' => 'required|integer'
        ]);

        $tasks = Task::where('id_dosen', $dosen->id)
            ->whereIn('id', $validated['task_ids'])
            ->get();

        foreach ($tasks as $task) {
            $task->delete(); // Cascade delete akan otomatis menghapus participants
        }

        return back()->with('success', 'Task berhasil dihapus (' . count($tasks) . ' task)');
    }

    /**
     * Bulk update status
     */
    public function bulkUpdateStatus(Request $request)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            return abort(403, 'Dosen tidak ditemukan');
        }

        $validated = $request->validate([
            'task_ids' => 'required|array',
            'task_ids.*' => 'required|integer'
        ]);

        $participants = Participant::whereIn('id_task', $validated['task_ids'])
            ->whereHas('task', function ($q) use ($dosen) {
                $q->where('id_dosen', $dosen->id);
            })
            ->get();

        $count = 0;
        foreach ($participants as $participant) {
            if ($participant->status_penyelesaian !== 'Selesai') {
                $participant->update(['status_penyelesaian' => 'Selesai']);
                $participant->mahasiswa->increment('jumlah_jam', $participant->task->jmlh_jam);
                $count++;
            }
        }

        return back()->with('success', 'Status peserta berhasil diperbarui (' . $count . ' peserta)');
    }
}

