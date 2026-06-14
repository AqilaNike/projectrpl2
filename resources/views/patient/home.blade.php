@extends('layouts.app')
@section('title', 'Beranda - Puskesmas Digital')
@section('body-class', 'pb-24 md:pb-0')

@section('content')
@include('layouts.patient-nav')

<main class="max-w-7xl mx-auto px-4 md:px-16 py-8">

    {{-- Flash success --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-secondary-container rounded-xl text-on-secondary-container text-sm">{{ session('success') }}</div>
    @endif

    {{-- Hero Section --}}
    <section class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        <div class="lg:col-span-2 space-y-6">
            <div>
                <h1 class="text-2xl md:text-4xl font-bold text-on-surface">Selamat Datang, {{ auth()->user()->name }}</h1>
                <p class="text-base text-on-surface-variant mt-2">
                    @if($activeQueue) Anda memiliki antrean aktif.
                    @else Belum ada antrean aktif. Silakan ambil antrean baru.
                    @endif
                </p>
            </div>

            @if($activeQueue)
            {{-- Active Queue Card --}}
            <div class="relative overflow-hidden rounded-xl bg-primary-container p-6 shadow-md border-l-8 border-primary">
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <span class="inline-block px-3 py-1 rounded-full bg-white/20 text-on-primary-container text-xs font-bold mb-3 uppercase">
                            {{ strtoupper($activeQueue->status) === 'MENUNGGU' ? 'ANTREAN BERJALAN' : 'SEDANG DIPANGGIL' }}
                        </span>
                        <div class="flex items-baseline gap-4">
                            <h2 class="text-6xl font-black text-on-primary-container">{{ $activeQueue->nomor_antrean }}</h2>
                            <span class="text-xl text-on-primary-container/80">{{ $activeQueue->poli->nama }}</span>
                        </div>
                        <div class="flex items-center gap-2 mt-2 text-on-primary-container">
                            <span class="material-symbols-outlined text-sm">event</span>
                            <span class="text-base font-medium">{{ \Carbon\Carbon::parse($activeQueue->tanggal)->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="flex items-center gap-2 mt-1 text-on-primary-container">
                            <span class="material-symbols-outlined text-sm">schedule</span>
                            <span class="text-base font-medium">Estimasi Dipanggil: {{ $activeQueue->estimasi_layanan ?? '-' }} WIB</span>
                        </div>
                    </div>
                    <a href="{{ route('patient.tiket', $activeQueue->id) }}"
                       class="w-full md:w-auto bg-on-primary-container text-primary font-bold py-3 px-8 rounded-xl hover:scale-105 transition-transform active:scale-95 shadow-lg text-center">
                        Lihat Detail Tiket
                    </a>
                </div>
                <div class="absolute top-0 right-0 -mr-12 -mt-12 w-48 h-48 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
            </div>
            @else
            <div class="rounded-xl bg-surface-container-low border border-outline-variant/30 p-6 flex flex-col md:flex-row gap-4 items-center">
                <span class="material-symbols-outlined text-primary text-5xl">add_circle</span>
                <div>
                    <h3 class="font-bold text-on-surface text-lg">Belum ada antrean hari ini</h3>
                    <p class="text-on-surface-variant text-sm">Ambil nomor antrean online sekarang dan hemat waktu tunggu di puskesmas.</p>
                </div>
                <a href="{{ route('patient.ambil-antrean') }}"
                   class="md:ml-auto bg-primary text-white font-bold py-3 px-8 rounded-xl hover:bg-primary/90 transition-all whitespace-nowrap">
                    Ambil Antrean
                </a>
            </div>
            @endif
        </div>

        {{-- Notifications sidebar --}}
        <div class="bg-surface-container-low rounded-xl p-6 shadow-sm border border-outline-variant/30 h-fit">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-sm font-bold text-on-surface">Notifikasi Terbaru</h3>
                <span class="text-xs text-primary font-bold">{{ $notifications->count() }} baru</span>
            </div>
            <div class="space-y-3">
                @forelse($notifications as $notif)
                <div class="flex gap-3 p-3 hover:bg-surface-container rounded-lg transition-colors">
                    <div class="w-10 h-10 rounded-full bg-secondary-container flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-on-secondary-container text-[18px]">{{ $notif->icon }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-on-surface">{{ $notif->judul }}</p>
                        <p class="text-xs text-on-surface-variant">{{ $notif->pesan }}</p>
                    </div>
                </div>
                @empty
                <p class="text-xs text-outline text-center py-4">Belum ada notifikasi.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Poli Cards --}}
    <section class="space-y-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-on-surface">Layanan Klinik</h2>
                <p class="text-base text-on-surface-variant">Pilih poli tujuan untuk mengambil antrean.</p>
            </div>
            <a href="{{ route('patient.ambil-antrean') }}"
               class="flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-xl font-bold shadow-md hover:bg-primary/90 transition-all active:scale-95">
                <span class="material-symbols-outlined">add_circle</span> Ambil Antrean Baru
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            @foreach($polis as $poli)
            <div class="group bg-surface-container-lowest border border-outline-variant/30 rounded-2xl p-6 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all {{ !$poli->is_active ? 'opacity-70' : '' }}">
                <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-primary">{{ $poli->icon }}</span>
                </div>
                <h3 class="font-bold text-on-surface mb-1">{{ $poli->nama }}</h3>
                <div class="flex items-center gap-2 mb-6">
                    @if(!$poli->is_active || $poli->kuota_tersisa <= 0)
                        <span class="w-2 h-2 rounded-full bg-red-500"></span>
                        <span class="text-xs font-bold" style="color:#ba1a1a">{{ !$poli->is_active ? 'Tutup' : 'Kuota Penuh' }}</span>
                    @elseif($poli->kuota_tersisa <= 5)
                        <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                        <span class="text-xs font-bold text-orange-700">{{ $poli->kuota_tersisa }} Kuota Tersisa</span>
                    @else
                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                        <span class="text-xs font-bold" style="color:#006c49">{{ $poli->kuota_tersisa }} Kuota Tersedia</span>
                    @endif
                </div>
                @if($poli->is_active && $poli->kuota_tersisa > 0)
                    <a href="{{ route('patient.ambil-antrean') }}?poli={{ $poli->id }}"
                       class="block w-full py-2 text-primary font-bold border border-primary/20 rounded-lg group-hover:bg-primary group-hover:text-white transition-colors text-center text-sm">
                        Pilih
                    </a>
                @else
                    <button disabled class="w-full py-2 bg-outline-variant/20 text-outline font-bold rounded-lg cursor-not-allowed text-sm">Tutup</button>
                @endif
            </div>
            @endforeach
        </div>
    </section>
</main>
@endsection
