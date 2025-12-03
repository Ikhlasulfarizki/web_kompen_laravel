<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin(){
        return view("login");
    }

    public function login(Request $request){
        $request->validate([
            "username" => 'required',
            'password'=> 'required',
        ]);
        $credentials = $request->only('username','password');
        if(Auth::attempt($credentials)){
            $user = Auth::user();
            switch ($user->role_id) {
                case 1:
                    return redirect()->route('admin.dashboard');
                case 2:
                    return redirect()->route('dosen.dashboard');
                case 3:
                    return redirect()->route('mahasiswa.dashboard');
            }
        }
        return back()->with('error', 'Username atau Password Salah');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
