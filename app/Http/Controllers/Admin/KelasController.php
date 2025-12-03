<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Prodi;
use App\Models\Jurusan;
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
        $jurusan = Jurusan::all();
        $prodi = Prodi::with('jurusan')->get();
        return view('admin.kelas.create', compact('jurusan', 'prodi'));
    }

    public function getProdi($id_jurusan)
    {
        $prodi = Prodi::where('id_jurusan', $id_jurusan)->get();
        return response()->json($prodi);
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
        $jurusan = Jurusan::all();
        $prodi = Prodi::with('jurusan')->get();
        return view('admin.kelas.edit', compact('kelas', 'jurusan', 'prodi'));
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
        try {
            $kelas = Kelas::findOrFail($id);
            $nama_kelas = $kelas->nama_kelas;

            // Cascade delete will automatically delete related mahasiswa
            $kelas->delete();

            return redirect()->route('admin.kelas.index')->with('success', "Kelas '$nama_kelas' dan semua data terkait (Mahasiswa) berhasil dihapus!");
        } catch (\Exception $e) {
            return redirect()->route('admin.kelas.index')->with('error', 'Gagal menghapus Kelas: ' . $e->getMessage());
        }
    }
}
