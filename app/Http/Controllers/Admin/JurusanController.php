<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    public function index()
    {
        $jurusan = Jurusan::all();
        return view('admin.jurusan.index', compact('jurusan'));
    }

    public function create()
    {
        return view('admin.jurusan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jurusan' => 'required|string|unique:jurusan',
        ]);

        Jurusan::create($request->all());

        return redirect()->route('admin.jurusan.index')->with('success', 'Jurusan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        return view('admin.jurusan.edit', compact('jurusan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_jurusan' => 'required|string|unique:jurusan,nama_jurusan,' . $id,
        ]);

        $jurusan = Jurusan::findOrFail($id);
        $jurusan->update($request->all());

        return redirect()->route('admin.jurusan.index')->with('success', 'Jurusan berhasil diubah!');
    }

    public function destroy($id)
    {
        try {
            $jurusan = Jurusan::findOrFail($id);
            $nama_jurusan = $jurusan->nama_jurusan;

            // Cascade delete will automatically delete related prodi, kelas, mahasiswa, dosen
            $jurusan->delete();

            return redirect()->route('admin.jurusan.index')->with('success', "Jurusan '$nama_jurusan' dan semua data terkait (Prodi, Kelas, Mahasiswa, Dosen) berhasil dihapus!");
        } catch (\Exception $e) {
            return redirect()->route('admin.jurusan.index')->with('error', 'Gagal menghapus Jurusan: ' . $e->getMessage());
        }
    }
}
