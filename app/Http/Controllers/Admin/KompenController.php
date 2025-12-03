<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Dosen;
use App\Models\Participant;
use Illuminate\Http\Request;

class KompenController extends Controller
{
    public function index()
    {
        $tasks = Task::with('dosen.user', 'participants')->get();
        return view('admin.kompen.index', compact('tasks'));
    }

    public function create()
    {
        $dosen = Dosen::with('user')->get();
        return view('admin.kompen.create', compact('dosen'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string',
            'deskripsi' => 'required|string',
            'lokasi' => 'required|string',
            'tanggal_waktu' => 'required|date_format:Y-m-d\TH:i',
            'kuota' => 'required|integer|min:1',    
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
            'jmlh_jam' => 'required|integer|min:1',
            'id_dosen' => 'required|exists:dosen,id',
        ]);

        $data = $request->all();
        // Convert datetime-local format (Y-m-d\TH:i) to database format (Y-m-d H:i:s)
        $data['tanggal_waktu'] = str_replace('T', ' ', $data['tanggal_waktu']) . ':00';
        // jam_mulai dan jam_selesai sudah dalam format HH:mm, tinggal simpan langsung

        Task::create($data);

        return redirect()->route('admin.kompen.index')->with('success', 'Task/Kompen berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $task = Task::with('dosen')->findOrFail($id);
        $dosen = Dosen::with('user')->get();
        return view('admin.kompen.edit', compact('task', 'dosen'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string',
            'deskripsi' => 'required|string',
            'lokasi' => 'required|string',
            'tanggal_waktu' => 'required|date_format:Y-m-d\TH:i',
            'kuota' => 'required|integer|min:1',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
            'jmlh_jam' => 'required|integer|min:1',
            'id_dosen' => 'required|exists:dosen,id',
        ]);

        $task = Task::findOrFail($id);
        $data = $request->all();
        // Convert datetime-local format (Y-m-d\TH:i) to database format (Y-m-d H:i:s)
        $data['tanggal_waktu'] = str_replace('T', ' ', $data['tanggal_waktu']) . ':00';
        // jam_mulai dan jam_selesai sudah dalam format HH:mm, tinggal simpan langsung
        
        $task->update($data);

        return redirect()->route('admin.kompen.index')->with('success', 'Task/Kompen berhasil diubah!');
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->participants()->delete();
        $task->delete();

        return redirect()->route('admin.kompen.index')->with('success', 'Task/Kompen berhasil dihapus!');
    }
}
