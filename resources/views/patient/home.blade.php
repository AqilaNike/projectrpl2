@extends('layouts.app')

@section('title', 'Beranda Pasien')

@section('content')
@include('layouts.patient-nav')
<main class="pt-24 pb-20 px-4 md:px-16 max-w-7xl mx-auto">
    <div class="space-y-6">
        <div class="grid gap-6 lg:grid-cols-3">
            <div class="rounded-3xl bg-surface-container p-6 shadow-sm border border-outline-variant">
                <p class="text-label-sm uppercase tracking-[0.25em] text-on-surface-variant">Halo, {{ $user->name }}</p>
                <h1 class="mt-3 text-3xl font-bold text-on-surface">Selamat datang di Puskesmas Digital</h1>
                <p class="mt-3 text-sm text-on-surface-variant">Akses antrean, notifikasi, dan jadwal layanan dengan mudah.</p>
            </div>
            <div class="rounded-3xl bg-surface-container p-6 shadow-sm border border-outline-variant">
                <p class="text-label-sm uppercase tracking-[0.25em] text-on-surface-variant">Antrean aktif</p>
                @if($activeQueue)
                    <div class="mt-4 space-y-3">
                        <div class="text-sm text-on-surface-variant">Nomor antrean</div>
                        <div class="text-4xl font-bold text-primary">{{ $activeQueue->nomor_antrean }}</div>
                        <div class="text-sm text-on-surface">Poli {{ $activeQueue->poli->nama }} — {{ $activeQueue->doctor->user->name }}</div>
                        <div class="text-sm text-on-surface-variant">Status: <span class="font-semibold">{{ ucfirst($activeQueue->status) }}</span></div>
                    </div>
                @else
                    <p class="mt-4 text-sm text-on-surface-variant">Tidak ada antrean aktif hari ini. Silakan ambil antrean bila diperlukan.</p>
                @endif
            </div>
            <div class="rounded-3xl bg-surface-container p-6 shadow-sm border border-outline-variant">
                <p class="text-label-sm uppercase tracking-[0.25em] text-on-surface-variant">Kuota tersedia</p>
                <ul class="mt-4 space-y-3">
                    @forelse($polis as $poli)
                        <li class="rounded-2xl bg-white p-4 border border-outline-variant">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-on-surface">{{ $poli->nama }}</p>
                                    <p class="text-xs text-on-surface-variant">Kode {{ $poli->kode }}</p>
                                </div>
                                <span class="rounded-full bg-secondary-container px-3 py-1 text-xs font-semibold text-on-secondary-container">{{ $poli->kuota_tersisa }} tersisa</span>
                            </div>
                        </li>
                    @empty
                        <li class="text-sm text-on-surface-variant">Tidak ada poli aktif saat ini.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <section class="rounded-3xl bg-surface-container p-6 shadow-sm border border-outline-variant">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-semibold text-on-surface">Notifikasi terbaru</h2>
                    <p class="text-sm text-on-surface-variant">5 notifikasi terakhir untuk akun Anda.</p>
                </div>
                <a href="{{ route('patient.ambil-antrean') }}" class="text-sm font-semibold text-primary">Ambil antrean</a>
            </div>
            <div class="mt-6 space-y-4">
                @forelse($notifications as $notification)
                    <div class="rounded-2xl bg-white p-4 border border-outline-variant">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-sm font-semibold text-on-surface">{{ $notification->judul }}</p>
                                <p class="text-xs text-on-surface-variant">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <p class="mt-3 text-sm text-on-surface-variant">{{ $notification->pesan }}</p>
                    </div>
                @empty
                    <p class="text-sm text-on-surface-variant">Belum ada notifikasi.</p>
                @endforelse
            </div>
        </section>
    </div>
</main>
@endsection
