@extends('layouts.app')

@section('title', 'Pengaturan - Admin Portal')

@section('content')
@include('layouts.admin-sidebar')
<main class="flex-1 md:ml-64 pt-8 pb-20 px-4 md:px-8 min-h-screen">
    <div class="max-w-5xl mx-auto space-y-8">
        <div>
            <h1 class="text-2xl font-semibold text-on-surface">Pengaturan Sistem</h1>
            <p class="mt-2 text-sm text-on-surface-variant">Konfigurasi akun admin dan preferensi aplikasi.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            {{-- Navigation/Tabs --}}
            <div class="md:col-span-1 space-y-2">
                <button id="btn-profil" onclick="switchTab('profil')" class="w-full text-left px-4 py-3 rounded-xl bg-primary-container text-on-primary-container font-bold text-sm flex items-center gap-3 transition-colors">
                    <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">person</span>
                    Profil Akun
                </button>
                <button id="btn-keamanan" onclick="switchTab('keamanan')" class="w-full text-left px-4 py-3 rounded-xl text-on-surface-variant hover:bg-surface-variant/50 font-semibold text-sm flex items-center gap-3 transition-colors">
                    <span class="material-symbols-outlined">lock</span>
                    Keamanan
                </button>
            </div>

            {{-- Form Area --}}
            <div class="md:col-span-3 space-y-6">
                
                {{-- Profil Section --}}
                <div id="section-profil" class="bg-white p-6 md:p-8 rounded-3xl shadow-sm border border-outline-variant transition-opacity duration-300">
                    <h2 class="text-lg font-bold text-on-surface mb-6 border-b border-outline-variant/30 pb-4">Informasi Pribadi</h2>
                    
                    <form action="#" class="space-y-6">
                        <div class="flex items-center gap-6 mb-8">
                            <div class="w-24 h-24 rounded-full bg-primary/10 flex items-center justify-center text-primary border border-primary/20 shrink-0">
                                <span class="material-symbols-outlined text-5xl">account_circle</span>
                            </div>
                            <div>
                                <button type="button" class="px-4 py-2 bg-white border border-outline-variant rounded-xl text-sm font-bold text-on-surface hover:bg-surface-variant/50 transition-colors">
                                    Ubah Foto
                                </button>
                                <p class="text-xs text-on-surface-variant mt-2">JPG, GIF atau PNG. Maksimal ukuran 2MB.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-on-surface-variant mb-2">Nama Lengkap</label>
                                <input type="text" value="{{ $user->name ?? 'Admin Utama' }}" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface-container/20 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-on-surface-variant mb-2">Email</label>
                                <input type="email" value="{{ $user->email ?? 'admin@puskesmas.id' }}" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface-container/20 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-sm font-semibold text-on-surface-variant mb-2">Role Akses</label>
                                <input type="text" value="{{ strtoupper($user->role ?? 'ADMIN') }}" disabled class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface-variant/30 text-on-surface-variant cursor-not-allowed font-medium">
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-sm font-semibold text-on-surface-variant mb-2">Zona Waktu</label>
                                <input type="text" value="WIB (Asia/Jakarta)" disabled class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface-variant/30 text-on-surface-variant cursor-not-allowed font-medium">
                            </div>
                        </div>

                        <div class="pt-6 mt-6 border-t border-outline-variant/30 flex justify-end gap-3">
                            <button type="button" class="px-6 py-2.5 bg-white border border-outline-variant text-on-surface-variant rounded-xl text-sm font-bold hover:bg-surface-variant/50 transition-colors">
                                Batal
                            </button>
                            <button type="button" class="px-6 py-2.5 bg-primary text-white rounded-xl text-sm font-bold hover:bg-primary/90 transition-colors shadow-sm">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Keamanan Section --}}
                <div id="section-keamanan" class="hidden bg-white p-6 md:p-8 rounded-3xl shadow-sm border border-outline-variant transition-opacity duration-300">
                    <h2 class="text-lg font-bold text-on-surface mb-6 border-b border-outline-variant/30 pb-4">Ubah Kata Sandi</h2>
                    
                    <form action="#" class="space-y-6">
                        <div class="bg-surface-container-low p-4 rounded-xl border border-outline-variant/50 mb-6 flex items-start gap-4">
                            <span class="material-symbols-outlined text-amber-500 mt-0.5">security</span>
                            <div>
                                <h3 class="text-sm font-bold text-on-surface">Pentingnya Kata Sandi Kuat</h3>
                                <p class="text-xs text-on-surface-variant mt-1">Pastikan kata sandi Anda memiliki minimal 8 karakter, menggabungkan huruf besar, huruf kecil, dan angka demi keamanan data medis.</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-on-surface-variant mb-2">Kata Sandi Saat Ini</label>
                            <input type="password" placeholder="Masukkan kata sandi lama" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface-container/20 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-on-surface-variant mb-2">Kata Sandi Baru</label>
                            <input type="password" placeholder="Masukkan kata sandi baru" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface-container/20 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-on-surface-variant mb-2">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" placeholder="Ulangi kata sandi baru" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface-container/20 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                        </div>

                        <div class="pt-6 mt-6 border-t border-outline-variant/30 flex justify-end gap-3">
                            <button type="button" class="px-6 py-2.5 bg-primary text-white rounded-xl text-sm font-bold hover:bg-primary/90 transition-colors shadow-sm">
                                Perbarui Kata Sandi
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    function switchTab(tab) {
        const btnProfil = document.getElementById('btn-profil');
        const btnKeamanan = document.getElementById('btn-keamanan');
        const sectionProfil = document.getElementById('section-profil');
        const sectionKeamanan = document.getElementById('section-keamanan');

        // Reset classes
        const activeBtnClasses = 'bg-primary-container text-on-primary-container font-bold'.split(' ');
        const inactiveBtnClasses = 'text-on-surface-variant hover:bg-surface-variant/50 font-semibold'.split(' ');

        if (tab === 'profil') {
            // Button styles
            btnProfil.classList.remove(...inactiveBtnClasses);
            btnProfil.classList.add(...activeBtnClasses);
            btnProfil.querySelector('span').style.fontVariationSettings = "'FILL' 1";
            
            btnKeamanan.classList.remove(...activeBtnClasses);
            btnKeamanan.classList.add(...inactiveBtnClasses);
            btnKeamanan.querySelector('span').style.fontVariationSettings = "normal";

            // Section visibility
            sectionProfil.classList.remove('hidden');
            sectionKeamanan.classList.add('hidden');
        } else {
            // Button styles
            btnKeamanan.classList.remove(...inactiveBtnClasses);
            btnKeamanan.classList.add(...activeBtnClasses);
            btnKeamanan.querySelector('span').style.fontVariationSettings = "'FILL' 1";
            
            btnProfil.classList.remove(...activeBtnClasses);
            btnProfil.classList.add(...inactiveBtnClasses);
            btnProfil.querySelector('span').style.fontVariationSettings = "normal";

            // Section visibility
            sectionKeamanan.classList.remove('hidden');
            sectionProfil.classList.add('hidden');
        }
    }
</script>
@endpush
