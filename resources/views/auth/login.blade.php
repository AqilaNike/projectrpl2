@extends('layouts.app')
@section('title', 'Login - Puskesmas Digital')
@section('body-class', 'flex items-center justify-center overflow-x-hidden p-4 md:p-8')

@section('content')
{{-- Floating blobs --}}
<div class="absolute w-96 h-96 rounded-full z-[-1] blur-[60px]" style="background:radial-gradient(circle,rgba(37,99,235,.1) 0%,transparent 70%);top:-100px;left:-100px;animation:float 20s infinite alternate ease-in-out"></div>
<div class="absolute w-96 h-96 rounded-full z-[-1] blur-[60px]" style="background:radial-gradient(circle,rgba(37,99,235,.1) 0%,transparent 70%);bottom:-100px;right:-100px;animation:float 20s infinite alternate ease-in-out;animation-delay:-5s"></div>

<main class="w-full max-w-6xl grid md:grid-cols-2 gap-8 items-center">
    {{-- Left branding --}}
    <section class="hidden md:flex flex-col gap-8 pr-8">
        <div class="space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center text-white shadow-lg">
                    <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">medical_services</span>
                </div>
                <h1 class="text-3xl font-bold text-primary tracking-tight">Puskesmas Digital</h1>
            </div>
            <h2 class="text-5xl font-bold leading-tight text-on-surface">Kesehatan Anda,<br>Prioritas Digital Kami.</h2>
            <p class="text-lg text-on-surface-variant max-w-md">
                Akses layanan kesehatan terpadu: pendaftaran antrean online, konsultasi, hingga riwayat medis dalam satu aplikasi modern yang aman dan terpercaya.
            </p>
        </div>
        <div class="flex gap-6 items-center">
            <div class="flex flex-col"><span class="text-3xl font-bold text-primary">24/7</span><span class="text-xs text-outline">Layanan Digital</span></div>
            <div class="w-px h-10 bg-outline-variant"></div>
            <div class="flex flex-col"><span class="text-3xl font-bold text-primary">50k+</span><span class="text-xs text-outline">Pasien Terdaftar</span></div>
            <div class="w-px h-10 bg-outline-variant"></div>
            <div class="flex flex-col"><span class="text-3xl font-bold text-primary">4</span><span class="text-xs text-outline">Poli Layanan</span></div>
        </div>
    </section>

    {{-- Right forms --}}
    <section class="w-full flex flex-col gap-6">
        <div class="bg-white/70 backdrop-blur-xl border border-white/30 rounded-3xl p-8 md:p-10 shadow-xl">

            {{-- Tabs --}}
            <div class="flex bg-surface-container-low p-1 rounded-xl mb-8">
                <button id="tab-login"    onclick="switchTab('login')"
                    class="flex-1 py-2 rounded-lg text-sm font-semibold transition-all bg-white text-primary shadow-sm">
                    Masuk
                </button>
                <button id="tab-register" onclick="switchTab('register')"
                    class="flex-1 py-2 rounded-lg text-sm font-semibold transition-all text-on-surface-variant hover:text-primary">
                    Daftar
                </button>
            </div>

            {{-- Errors --}}
            @if($errors->any())
                <div class="mb-4 p-3 bg-error-container rounded-xl text-on-error-container text-sm">
                    @foreach($errors->all() as $e) <p>• {{ $e }}</p> @endforeach
                </div>
            @endif

            {{-- LOGIN FORM --}}
            <div id="login-section" class="space-y-6">
                <div>
                    <h3 class="text-2xl font-semibold text-on-surface">Selamat Datang</h3>
                    <p class="text-base text-on-surface-variant">Silakan masuk untuk mengakses layanan kesehatan Anda.</p>
                </div>
                <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                    @csrf
                    <div class="space-y-1.5">
                        <label class="text-sm font-semibold text-on-surface-variant ml-1">NIK / BPJS / Email / No. HP</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">person</span>
                            <input name="identifier" value="{{ old('identifier') }}" type="text" placeholder="Masukkan identitas Anda"
                                class="w-full pl-12 pr-4 py-3.5 bg-white border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none text-on-surface transition-all">
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center ml-1">
                            <label class="text-sm font-semibold text-on-surface-variant">Kata Sandi</label>
                            <a href="#" class="text-xs text-primary hover:underline">Lupa Sandi?</a>
                        </div>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">lock</span>
                            <input name="password" type="password" placeholder="••••••••"
                                class="w-full pl-12 pr-4 py-3.5 bg-white border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none text-on-surface transition-all">
                        </div>
                    </div>
                    <button type="submit"
                        class="w-full bg-primary text-white py-4 rounded-xl text-sm font-bold shadow-lg shadow-primary/20 hover:bg-primary/90 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-sm">login</span> Masuk Ke Akun
                    </button>
                </form>
            </div>

            {{-- REGISTER FORM --}}
            <div id="register-section" class="hidden space-y-6">
                <div>
                    <h3 class="text-2xl font-semibold text-on-surface">Buat Akun Baru</h3>
                    <p class="text-base text-on-surface-variant">Lengkapi data diri untuk pendaftaran pasien baru.</p>
                </div>
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="form_type" value="register">
                    <div>
                        <label class="text-sm font-semibold text-on-surface-variant ml-1 block mb-1.5">Nama Lengkap Sesuai KTP</label>
                        <input name="name" value="{{ old('name') }}" type="text" placeholder="Contoh: Budi Santoso"
                            class="w-full px-4 py-3 bg-white border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary outline-none transition-all">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-on-surface-variant ml-1 block mb-1.5">NIK (16 digit)</label>
                            <input name="nik" value="{{ old('nik') }}" type="text" maxlength="16" placeholder="3275XXXXXXXXXXXX"
                                class="w-full px-4 py-3 bg-white border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary outline-none transition-all">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-on-surface-variant ml-1 block mb-1.5">No. BPJS (Opsional)</label>
                            <input name="no_bpjs" value="{{ old('no_bpjs') }}" type="text" placeholder="No. Kartu"
                                class="w-full px-4 py-3 bg-white border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary outline-none transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-on-surface-variant ml-1 block mb-1.5">Nomor WhatsApp</label>
                        <div class="flex gap-2">
                            <div class="bg-surface-container-high border border-outline-variant px-3 flex items-center rounded-xl text-on-surface-variant text-sm font-semibold">+62</div>
                            <input name="no_hp" value="{{ old('no_hp') }}" type="tel" placeholder="812xxxxxx"
                                class="flex-1 px-4 py-3 bg-white border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary outline-none transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-on-surface-variant ml-1 block mb-1.5">Kata Sandi</label>
                        <input name="password" type="password" placeholder="Minimal 6 karakter"
                            class="w-full px-4 py-3 bg-white border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary outline-none transition-all">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-on-surface-variant ml-1 block mb-1.5">Konfirmasi Kata Sandi</label>
                        <input name="password_confirmation" type="password" placeholder="Ulangi kata sandi"
                            class="w-full px-4 py-3 bg-white border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary outline-none transition-all">
                    </div>
                    <button type="submit"
                        class="w-full py-4 rounded-xl text-sm font-bold text-white shadow-lg active:scale-[0.98] transition-all"
                        style="background-color:#006c49">
                        Daftar Sekarang
                    </button>
                    <p class="text-center text-xs text-outline">
                        Dengan mendaftar, Anda menyetujui <a href="#" class="text-primary hover:underline">Syarat & Ketentuan</a>.
                    </p>
                </form>
            </div>
        </div>

        <div class="flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-primary text-sm">verified_user</span>
            <span class="text-xs text-on-surface-variant">Data terenkripsi & aman standar Kemenkes</span>
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>
function switchTab(type) {
    const loginSec  = document.getElementById('login-section');
    const regSec    = document.getElementById('register-section');
    const tabLogin  = document.getElementById('tab-login');
    const tabReg    = document.getElementById('tab-register');
    if (type === 'login') {
        loginSec.classList.remove('hidden'); regSec.classList.add('hidden');
        tabLogin.classList.add('bg-white','text-primary','shadow-sm'); tabLogin.classList.remove('text-on-surface-variant');
        tabReg.classList.remove('bg-white','text-primary','shadow-sm'); tabReg.classList.add('text-on-surface-variant');
    } else {
        loginSec.classList.add('hidden'); regSec.classList.remove('hidden');
        tabReg.classList.add('bg-white','text-primary','shadow-sm'); tabReg.classList.remove('text-on-surface-variant');
        tabLogin.classList.remove('bg-white','text-primary','shadow-sm'); tabLogin.classList.add('text-on-surface-variant');
    }
}
// If the register form was submitted, the user visited /register, or there are registration validation errors, show register tab
@if(old('form_type') === 'register' || ($showRegister ?? false) || $errors->has('nik') || $errors->has('name') || $errors->has('no_hp') || $errors->has('no_bpjs') || $errors->has('password_confirmation'))
switchTab('register');
@endif
</script>
@endpush
