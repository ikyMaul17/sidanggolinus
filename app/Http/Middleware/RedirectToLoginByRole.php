<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectToLoginByRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        // Jika pengguna belum login
        if (!Auth::check()) {
            if ($role === 'admin') {
                return redirect()->route('login');
            } elseif ($role === 'penumpang') {
                return redirect()->route('login_penumpang');
            } elseif ($role === 'supir') {
                return redirect()->route('login_supir');
            }
        }

        // Jika pengguna login, tapi perannya tidak sesuai
        if (Auth::user()->role !== $role) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
