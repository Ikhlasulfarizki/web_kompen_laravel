<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
        public function handle(Request $request, Closure $next, $role)
    {
        // Jika user tidak login → lempar ke login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Cek apakah role sesuai
        if (Auth::user()->role_id != $role) {
            return abort(403, "Anda tidak memiliki akses.");
        }

        return $next($request);
    }
}
