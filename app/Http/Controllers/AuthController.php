<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Models\Pasien;
use App\Models\Notifikasi;
use App\Models\Antrean;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

        // Try login with email first
        $pengguna = Pengguna::where('email', $identifier)->first();

        // If not found by email, try by NIK or phone via pasien table
        if (!$pengguna) {
            $pasien = Pasien::where('nik', $identifier)
                ->orWhere('nomorhp', $identifier)
                ->first();
            if ($pasien) {
                $pengguna = $pasien->pengguna;
            }
        }

        if (!$pengguna || !Hash::check($request->password, $pengguna->passwordhash)) {
            return back()->withErrors(['identifier' => 'Identitas atau kata sandi salah.'])->withInput();
        }

        if ($pengguna->statusakun !== 'aktif') {
            return back()->withErrors(['identifier' => 'Akun Anda tidak aktif.'])->withInput();
        }

        Auth::login($pengguna, $request->boolean('remember'));

        if ($pengguna->isAdmin() || $pengguna->isDokter() || $pengguna->isPetugas() || $pengguna->isKepala()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('patient.home');
    }

    public function showRegister()
    {
        return view('auth.login', ['showRegister' => true]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'nik'      => 'required|digits:16|unique:pasien,nik',
            'no_hp'    => 'required|string|min:9|max:15',
            'email'    => 'nullable|email|unique:pengguna,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $idpengguna = 'USR' . Str::upper(Str::random(9));
        $idpasien   = 'PSN' . Str::upper(Str::random(9));

        $pengguna = Pengguna::create([
            'idpengguna'   => $idpengguna,
            'email'        => $request->email ?? $request->nik . '@puskesmas.local',
            'passwordhash' => Hash::make($request->password),
            'role'         => 'pasien',
            'statusakun'   => 'aktif',
        ]);

        Pasien::create([
            'idpasien'    => $idpasien,
            'idpengguna'  => $idpengguna,
            'nik'         => $request->nik,
            'namapasien'  => $request->name,
            'nomorhp'     => $request->no_hp,
        ]);

        Auth::login($pengguna);
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
