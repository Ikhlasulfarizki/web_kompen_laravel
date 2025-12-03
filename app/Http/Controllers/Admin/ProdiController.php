<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    public function index()
    {
        $prodi = Prodi::with('jurusan')->get();
        return view('admin.prodi.index', compact('prodi'));
    }

    public function create()
    {
        $jurusan = Jurusan::all();
        return view('admin.prodi.create', compact('jurusan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_prodi' => 'required|string',
            'id_jurusan' => 'required|exists:jurusan,id',
        ]);

        Prodi::create($request->all());

        return redirect()->route('admin.prodi.index')->with('success', 'Program Studi berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $prodi = Prodi::with('jurusan')->findOrFail($id);
        $jurusan = Jurusan::all();
        return view('admin.prodi.edit', compact('prodi', 'jurusan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_prodi' => 'required|string',
            'id_jurusan' => 'required|exists:jurusan,id',
        ]);

        $prodi = Prodi::findOrFail($id);
        $prodi->update($request->all());

        return redirect()->route('admin.prodi.index')->with('success', 'Program Studi berhasil diubah!');
    }

    public function destroy($id)
    {
        $prodi = Prodi::findOrFail($id);
        $prodi->delete();

        return redirect()->route('admin.prodi.index')->with('success', 'Program Studi berhasil dihapus!');
    }
}
