<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswa = Mahasiswa::with('kelas.prodi.jurusan', 'user')->get();
        return view('admin.mahasiswa.index', compact('mahasiswa'));
    }

    public function create()
    {
        $jurusan = Jurusan::all();
        return view('admin.mahasiswa.create', compact('jurusan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'npm' => 'required|unique:mahasiswa',
            'nama' => 'required|string',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'id_kelas' => 'required|exists:kelas,id',
        ]);

        $user = User::create([
            'username' => $request->npm,
            'password' => date("dmY", strtotime($request->tgl_lahir)),
            'role_id' => 3, // Role mahasiswa
        ]);

        Mahasiswa::create([
            'user_id' => $user->id,
            'npm' => $request->npm,
            'tgl_lahir' => $request->tgl_lahir,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'id_kelas' => $request->id_kelas,
        ]);

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Mahasiswa berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $mahasiswa = Mahasiswa::with('kelas.prodi.jurusan')->findOrFail($id);
        $jurusan = Jurusan::all();
        return view('admin.mahasiswa.edit', compact('mahasiswa', 'jurusan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'npm' => 'required|unique:mahasiswa,npm,' . $id,
            'nama' => 'required|string',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'id_kelas' => 'required|exists:kelas,id',
        ]);

        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->update($request->all());

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil diubah!');
    }

    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        // Hapus mahasiswa (user akan otomatis terhapus karena onDelete cascade)
        $mahasiswa->delete();

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil dihapus!');
    }

    public function getProdi($id_jurusan)
    {
        $prodi = \App\Models\Prodi::where('id_jurusan', $id_jurusan)->get();
        return response()->json($prodi);
    }

    public function getKelas($id_prodi)
    {
        $kelas = Kelas::where('id_prodi', $id_prodi)->get();
        return response()->json($kelas);
    }
}
