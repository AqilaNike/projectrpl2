@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
@include('layouts.admin-sidebar')
<main class="pt-24 pb-20 px-4 md:px-16 max-w-7xl mx-auto">
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-3xl bg-surface-container p-6 shadow-sm border border-outline-variant">
            <p class="text-sm uppercase tracking-[0.25em] text-on-surface-variant">Total antrean hari ini</p>
            <h2 class="mt-3 text-3xl font-bold text-on-surface">{{ $stats['total_pasien'] }}</h2>
        </div>
        <div class="rounded-3xl bg-surface-container p-6 shadow-sm border border-outline-variant">
            <p class="text-sm uppercase tracking-[0.25em] text-on-surface-variant">Antrean menunggu</p>
            <h2 class="mt-3 text-3xl font-bold text-on-surface">{{ $stats['menunggu'] }}</h2>
        </div>
        <div class="rounded-3xl bg-surface-container p-6 shadow-sm border border-outline-variant">
            <p class="text-sm uppercase tracking-[0.25em] text-on-surface-variant">Dokter aktif</p>
            <h2 class="mt-3 text-3xl font-bold text-on-surface">{{ $doctors->count() }}</h2>
        </div>
    </div>

    <section class="mt-8 rounded-3xl bg-white p-6 border border-outline-variant shadow-sm">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h3 class="text-xl font-semibold text-on-surface">Ringkasan antrean hari ini</h3>
                <p class="text-sm text-on-surface-variant">Statistik antrean, dokter, dan layanan aktif.</p>
            </div>
            <a href="{{ route('admin.antrean') }}" class="text-sm font-semibold text-primary">Lihat daftar antrean</a>
        </div>
        <div class="mt-6 grid gap-4 md:grid-cols-2">
            <div class="rounded-3xl bg-surface-container p-5 border border-outline-variant">
                <p class="text-sm text-on-surface-variant">Antrean selesai</p>
                <p class="mt-3 text-2xl font-bold text-on-surface">{{ $stats['selesai'] }}</p>
            </div>
            <div class="rounded-3xl bg-surface-container p-5 border border-outline-variant">
                <p class="text-sm text-on-surface-variant">Antrean batal</p>
                <p class="mt-3 text-2xl font-bold text-on-surface">{{ $stats['batal'] }}</p>
            </div>
        </div>
    </section>
</main>
@endsection
