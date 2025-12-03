<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Prodi;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with('prodi.jurusan')->get();
        return view('admin.kelas.index', compact('kelas'));
    }

    public function create()
    {
        $prodi = Prodi::with('jurusan')->get();
        return view('admin.kelas.create', compact('prodi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string',
            'id_prodi' => 'required|exists:prodi,id',
        ]);

        Kelas::create($request->all());

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $kelas = Kelas::with('prodi.jurusan')->findOrFail($id);
        $prodi = Prodi::with('jurusan')->get();
        return view('admin.kelas.edit', compact('kelas', 'prodi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kelas' => 'required|string',
            'id_prodi' => 'required|exists:prodi,id',
        ]);

        $kelas = Kelas::findOrFail($id);
        $kelas->update($request->all());

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil diubah!');
    }

    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil dihapus!');
    }
}
