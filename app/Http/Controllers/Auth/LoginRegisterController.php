<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class LoginRegisterController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('guest', except: ['home', 'logout']),
            new Middleware('auth', only: ['home', 'logout']),
        ];
    }

    public function register(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|string|email:rfc,dns|max:250|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);
        $request->session()->regenerate();

        return redirect()->route('home')
            ->withSuccess('You have successfully registered & logged in!');
    }

    public function login(): View
    {
        return view('auth.login');
    }

    public function login_penumpang(): View
    {
        return view('page_penumpang.login');
    }

    public function login_supir(): View
    {
        return view('page_supir.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Periksa peran pengguna dan status aktif
            $user = Auth::user();
            if ($user->status === 'aktif') {
                if ($user->role === 'admin') {
                    return redirect()->route('dashboard');
                } elseif ($user->role === 'penumpang') {
                    return redirect()->route('home');
                } elseif ($user->role === 'supir') {
                    $bus = DB::table('bus')->where('id', $user->id_bus)->first();
                    if ($bus->rute == 'pergi') {
                        $halte = DB::table('halte_pergi')->orderBy('id', 'asc')->first();
                    } else {
                        $halte = DB::table('halte_pulang')->orderBy('id', 'asc')->first();
                    }

                    $delete = DB::table('tracking')->where('id_supir', $user->id)->delete();

                    DB::table('tracking')->insert([
                        [
                            'id_supir' => $user->id,
                            'id_bus' => $user->id_bus,
                            'rute' => $bus->rute,
                            'id_halte' => $halte->id,
                            'kapasitas' => 0,
                        ],
                    ]);

                    return redirect()->route('home_supir');
                }
            } else {
                Auth::logout();

                return redirect()->back()->with('success', 'Akun Anda tidak aktif.');
            }
        }

        return redirect()->back()->with('success', 'Data yang anda masukkan tidak cocok');
    }

    public function authenticate_admin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Periksa peran pengguna dan status aktif
            $user = Auth::user();
            if ($user->status === 'aktif') {
                if ($user->role === 'admin') {
                    return redirect()->route('dashboard');
                } else {
                    Auth::logout();

                    return redirect()->back()->with('error', 'Anda tidak dapat mengakses admin');
                }
            } else {
                Auth::logout();

                return redirect()->back()->with('success', 'Akun Anda tidak aktif.');
            }
        }

        return redirect()->back()->with('success', 'Data yang anda masukkan tidak cocok');
    }

    public function home(): View
    {
        return view('auth.home');
    }

    // public function logout(Request $request): RedirectResponse
    // {
    //     Auth::logout();
    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();
    //     return redirect()->route('login')
    //         ->withSuccess('You have logged out successfully!');
    // }

    public function logout()
    {
        // Dapatkan peran pengguna sebelum logout
        $role = Auth::user()->role;

        // Logout pengguna
        Auth::logout();

        // Arahkan ke halaman berbeda berdasarkan role
        if ($role === 'admin') {
            return redirect()->route('login');
        } elseif ($role === 'penumpang') {
            return redirect('/');
        } elseif ($role === 'supir') {
            return redirect()->route('login_supir');
        }

        return redirect('/');
    }
}
