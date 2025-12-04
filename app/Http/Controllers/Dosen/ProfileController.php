<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display the dosen profile
     */
    public function show()
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            return abort(403, 'Dosen tidak ditemukan');
        }

        $dosen->load(['prodi.jurusan']);

        return view('dosen.profile.show', compact('dosen', 'user'));
    }

    /**
     * Show the form for editing the dosen profile
     */
    public function edit()
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            return abort(403, 'Dosen tidak ditemukan');
        }

        $dosen->load(['prodi.jurusan']);

        return view('dosen.profile.edit', compact('dosen', 'user'));
    }

    /**
     * Update the dosen profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            return abort(403, 'Dosen tidak ditemukan');
        }

        $validated = $request->validate([
            'nama_dosen' => 'required|string|max:255',
            'nip' => 'required|string|max:255|unique:dosen,nip,' . $dosen->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nomor_hp' => 'nullable|string|max:20',
            'password' => 'nullable|min:8|confirmed',
        ]);

        // Update user data
        $userData = [
            'email' => $validated['email'],
        ];

        if ($validated['password'] ?? null) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);

        // Update dosen data
        $dosenData = [
            'nama_dosen' => $validated['nama_dosen'],
            'nip' => $validated['nip'],
            'nomor_hp' => $validated['nomor_hp'] ?? null,
        ];

        $dosen->update($dosenData);

        return redirect()->route('dosen.profile.show')
            ->with('success', 'Profil berhasil diperbarui');
    }
}
