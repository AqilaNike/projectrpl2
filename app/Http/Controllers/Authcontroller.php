<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'password'   => 'required|string',
        ]);

        $identifier = $request->identifier;

        // Try login with email, NIK, BPJS, or phone
        $user = User::where('email', $identifier)
            ->orWhere('nik', $identifier)
            ->orWhere('no_bpjs', $identifier)
            ->orWhere('no_hp', $identifier)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['identifier' => 'Identitas atau kata sandi salah.'])->withInput();
        }

        Auth::login($user, $request->boolean('remember'));

        if ($user->isAdmin() || $user->isDoctor()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('patient.home');
    }

    public function showRegister()
    {
        return view('auth.login'); // same page, tab=register
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'nik'    => 'required|digits:16|unique:users,nik',
            'no_bpjs'=> 'nullable|string|unique:users,no_bpjs',
            'no_hp'  => 'required|string|min:9|max:15',
            'password'=> 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name'    => $request->name,
            'nik'     => $request->nik,
            'no_bpjs' => $request->no_bpjs,
            'no_hp'   => $request->no_hp,
            'email'   => $request->email,
            'password'=> Hash::make($request->password),
            'role'    => 'patient',
        ]);

        Notifikasi::create([
            'user_id' => $user->id,
            'judul'   => 'Selamat Datang di Puskesmas Digital!',
            'pesan'   => 'Akun Anda berhasil dibuat. Silakan ambil antrean untuk layanan kesehatan Anda.',
            'icon'    => 'check_circle',
        ]);

        Auth::login($user);
        return redirect()->route('patient.home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
