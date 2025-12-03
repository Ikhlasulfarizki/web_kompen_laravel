<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('admin.profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('admin.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'username' => 'required|unique:users,username,' . $user->id,
            'password' => 'nullable|min:3|confirmed',
        ]);

        $data = ['username' => $request->username];
        
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        /** @var User $user */
        $user->update($data);

        return redirect()->route('admin.profile.show')->with('success', 'Profil berhasil diperbarui!');
    }
}
