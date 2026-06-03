@extends('layouts.app')

@section('title', 'Login - Puskesmas Digital')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-xl overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2">
            <div class="bg-primary text-white p-10 flex flex-col justify-center gap-6">
                <div>
                    <h1 class="text-4xl font-bold">Puskesmas Digital</h1>
                    <p class="mt-3 text-sm text-primary-container">Aplikasi antrean online untuk layanan kesehatan Anda.</p>
                </div>
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em]">Masuk atau daftar</p>
                    <p class="text-sm leading-6 text-primary-container">Masukkan identitas Anda untuk melanjutkan. Anda dapat login memakai email, NIK, BPJS, atau nomor telepon.</p>
                </div>
            </div>
            <div class="p-8 md:p-10">
                <div class="space-y-6">
                    <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                        @csrf
                        <h2 class="text-2xl font-semibold">Login</h2>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium">Email / NIK / BPJS / No HP</label>
                            <input type="text" name="identifier" value="{{ old('identifier') }}" class="w-full rounded-xl border border-outline-variant px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Masukkan identitas Anda">
                            @error('identifier')<p class="text-sm text-error mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium">Kata Sandi</label>
                            <input type="password" name="password" class="w-full rounded-xl border border-outline-variant px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="********">
                            @error('password')<p class="text-sm text-error mt-1">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit" class="w-full rounded-xl bg-primary px-4 py-3 text-white font-semibold hover:bg-primary-container transition">Masuk</button>
                    </form>
                    <div class="border-t border-outline-variant pt-5">
                        <h3 class="text-lg font-semibold">Belum punya akun?</h3>
                        <p class="text-sm text-on-surface-variant mt-2">Daftar sebagai pasien baru dengan mengisi formulir di bawah.</p>
                        <form method="POST" action="{{ route('register') }}" class="mt-5 space-y-4">
                            @csrf
                            <div class="grid gap-4 md:grid-cols-2">
                                <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-xl border border-outline-variant px-4 py-3" placeholder="Nama lengkap">
                                <input type="text" name="nik" value="{{ old('nik') }}" class="w-full rounded-xl border border-outline-variant px-4 py-3" placeholder="NIK">
                                <input type="text" name="no_hp" value="{{ old('no_hp') }}" class="w-full rounded-xl border border-outline-variant px-4 py-3" placeholder="No HP">
                                <input type="text" name="email" value="{{ old('email') }}" class="w-full rounded-xl border border-outline-variant px-4 py-3" placeholder="Email (opsional)">
                                <input type="password" name="password" class="w-full rounded-xl border border-outline-variant px-4 py-3" placeholder="Kata sandi">
                                <input type="password" name="password_confirmation" class="w-full rounded-xl border border-outline-variant px-4 py-3" placeholder="Ulang kata sandi">
                            </div>
                            <button type="submit" class="w-full rounded-xl bg-secondary px-4 py-3 text-white font-semibold hover:bg-secondary-container transition">Daftar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
