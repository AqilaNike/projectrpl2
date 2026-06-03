@extends('layouts.app')

@section('title', 'Monitor Antrean')

@section('content')
@include('layouts.admin-sidebar')
<main class="pt-24 pb-20 px-4 md:px-16 max-w-7xl mx-auto">
    <div class="rounded-3xl bg-surface-container p-6 shadow-sm border border-outline-variant">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-on-surface">Monitor Antrean</h1>
                <p class="mt-2 text-sm text-on-surface-variant">Pantau antrean pasien secara real-time.</p>
            </div>
            <a href="{{ route('admin.antrean') }}" class="rounded-full bg-primary px-4 py-2 text-sm font-semibold text-white">Lihat Semua Antrean</a>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-2">
            <div class="rounded-3xl bg-white p-6 border border-outline-variant">
                <h2 class="text-lg font-semibold text-on-surface">Antrean Dipanggil</h2>
                @if($dipanggil)
                    <div class="mt-6 space-y-3">
                        <p class="text-sm text-on-surface-variant">Nomor antrean</p>
                        <p class="text-4xl font-bold text-primary">{{ $dipanggil->nomor_antrean }}</p>
                        <p class="text-sm text-on-surface">Pasien: {{ $dipanggil->user->name }}</p>
                        <p class="text-sm text-on-surface">Poli: {{ $dipanggil->poli->nama }}</p>
                        <p class="text-sm text-on-surface">Dokter: {{ $dipanggil->doctor->user->name }}</p>
                    </div>
                @else
                    <p class="mt-4 text-sm text-on-surface-variant">Belum ada antrean yang sedang dipanggil.</p>
                @endif
            </div>

            <div class="rounded-3xl bg-white p-6 border border-outline-variant">
                <h2 class="text-lg font-semibold text-on-surface">Antrean Berikutnya</h2>
                @if($berikutnya->isNotEmpty())
                    <div class="mt-6 space-y-4">
                        @foreach($berikutnya as $item)
                            <div class="rounded-2xl bg-surface-container p-4">
                                <p class="font-semibold text-on-surface">{{ $item->nomor_antrean }} — {{ $item->user->name }}</p>
                                <p class="text-sm text-on-surface-variant">{{ $item->poli->nama }} • {{ $item->tanggal->format('d M Y') }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="mt-4 text-sm text-on-surface-variant">Tidak ada antrean berikutnya untuk hari ini.</p>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection
