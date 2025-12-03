<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
            'role_id' => 'required|exists:roles,id',
        ]);

        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $oldRoleId = $user->role_id;
        $newRoleId = $request->role_id;

        $request->validate([
            'username' => 'required|unique:users,username,' . $id,
            'role_id' => 'required|exists:roles,id',
        ]);

        $data = [
            'username' => $request->username,
            'role_id' => $request->role_id,
        ];

        // Hanya update password jika ada input password
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Handle role change dari mahasiswa ke dosen
        if ($oldRoleId == 3 && $newRoleId == 2) {
            // Dari mahasiswa ke dosen
            $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

            if ($mahasiswa) {
                // Check apakah sudah ada dosen record
                $dosenExist = Dosen::where('user_id', $user->id)->exists();

                if (!$dosenExist) {
                    // Create dosen record dengan data dari mahasiswa
                    Dosen::create([
                        'user_id' => $user->id,
                        'nip' => $mahasiswa->npm, // Gunakan NPM sebagai NIP sementara
                        'tgl_lahir' => $mahasiswa->tgl_lahir,
                        'nama' => $mahasiswa->nama,
                        'jenis_kelamin' => $mahasiswa->jenis_kelamin,
                        'id_prodi' => $mahasiswa->kelas->prodi->id ?? null, // Ambil prodi dari kelas mahasiswa
                    ]);

                    return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui dan dipromosikan menjadi Dosen!');
                }
            }
        }

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui!');
    }

    public function destroy($id)
    {
        /** @var User $user */
        $user = User::findOrFail($id);

        // Cegah penghapusan user sendiri
        if ($user->id === Auth::user()->id) {
            return redirect()->route('admin.users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus!');
    }
}
