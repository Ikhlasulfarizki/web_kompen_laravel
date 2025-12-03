<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Prodi;
use App\Models\Jurusan;
use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DosenController extends Controller
{
    public function index()
    {
        $dosen = Dosen::with('prodi.jurusan', 'user')->get();
        return view('admin.dosen.index', compact('dosen'));
    }

    public function create()
    {
        $jurusan = Jurusan::all();
        $prodi = Prodi::with('jurusan')->get();
        return view('admin.dosen.create', compact('jurusan', 'prodi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:dosen',
            'nama' => 'required|string',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'id_prodi' => 'required|exists:prodi,id',
        ]);

        $user = User::create([
            'username' => $request->nip,
            'password' => date("dmY", strtotime($request->tgl_lahir)),
            'role_id' => 2, // Role dosen
        ]);

        Dosen::create([
            'user_id' => $user->id,
            'nip' => $request->nip,
            'tgl_lahir' => $request->tgl_lahir,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'id_prodi' => $request->id_prodi,
        ]);

        return redirect()->route('admin.dosen.index')->with('success', 'Dosen berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $dosen = Dosen::with('prodi.jurusan')->findOrFail($id);
        $jurusan = Jurusan::all();
        $prodi = Prodi::with('jurusan')->get();
        return view('admin.dosen.edit', compact('dosen', 'jurusan', 'prodi'));
    }

    public function getProdi($id_jurusan)
    {
        $prodi = Prodi::where('id_jurusan', $id_jurusan)->get();
        return response()->json($prodi);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nip' => 'required|unique:dosen,nip,' . $id,
            'nama' => 'required|string',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'id_prodi' => 'required|exists:prodi,id',
        ]);

        $dosen = Dosen::findOrFail($id);
        $dosen->update($request->all());

        return redirect()->route('admin.dosen.index')->with('success', 'Data dosen berhasil diubah!');
    }

    public function destroy($id)
    {
        $dosen = Dosen::findOrFail($id);

        // Hapus dosen (tasks dan user akan otomatis terhapus karena onDelete cascade)
        $dosen->delete();

        return redirect()->route('admin.dosen.index')->with('success', 'Data dosen berhasil dihapus!');
    }
}
