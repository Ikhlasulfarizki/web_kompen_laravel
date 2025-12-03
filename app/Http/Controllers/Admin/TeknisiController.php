<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teknisi;
use App\Models\User;
use Illuminate\Http\Request;

class TeknisiController extends Controller
{
    public function index()
    {
        $teknisi = Teknisi::with('user')->get();
        return view('admin.teknisi.index', compact('teknisi'));
    }

    public function create()
    {
        return view('admin.teknisi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:teknisi',
            'nama' => 'required|string',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        ]);

        $user = User::create([
            'username' => $request->nip,
            'password' => date("dmY", strtotime($request->tgl_lahir)),
            'role_id' => 4, // Role teknisi
        ]);

        Teknisi::create([
            'user_id' => $user->id,
            'nip' => $request->nip,
            'tgl_lahir' => $request->tgl_lahir,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        return redirect()->route('admin.teknisi.index')->with('success', 'Teknisi berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $teknisi = Teknisi::findOrFail($id);
        return view('admin.teknisi.edit', compact('teknisi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nip' => 'required|unique:teknisi,nip,' . $id,
            'nama' => 'required|string',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        ]);

        $teknisi = Teknisi::findOrFail($id);
        $teknisi->update($request->all());

        return redirect()->route('admin.teknisi.index')->with('success', 'Data teknisi berhasil diubah!');
    }

    public function destroy($id)
    {
        $teknisi = Teknisi::findOrFail($id);
        $user_id = $teknisi->user_id;

        // Hapus teknisi terlebih dahulu
        $teknisi->delete();

        // Kemudian hapus user
        if ($user_id) {
            User::find($user_id)?->delete();
        }

        return redirect()->route('admin.teknisi.index')->with('success', 'Data teknisi berhasil dihapus!');
    }
}
