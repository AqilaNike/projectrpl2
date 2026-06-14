 @extends('layouts.app')

@section('title', 'Tiket Antrean')

@section('content')
@include('layouts.patient-nav')
<main class="pt-24 pb-20 px-4 md:px-16 max-w-4xl mx-auto">
    <div class="rounded-3xl bg-surface-container p-8 shadow-sm border border-outline-variant">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-label-sm uppercase tracking-[0.25em] text-on-surface-variant">Tiket Antrean</p>
                <h1 class="text-3xl font-bold text-on-surface">{{ $antrean->nomorantrean }}</h1>
            </div>
            <div class="rounded-2xl bg-white px-4 py-3 border border-outline-variant text-sm text-on-surface-variant">
                {{ $antrean->jadwal->tanggal->format('d M Y') }} • {{ $antrean->jam_kedatangan }}
            </div>
        </div>

        <div class="mt-8 grid gap-4 md:grid-cols-2">
            <div class="rounded-3xl bg-white p-6 border border-outline-variant">
                <h2 class="text-lg font-semibold text-on-surface">Detail Pasien</h2>
                <p class="mt-4 text-sm text-on-surface-variant">Nama</p>
                <p class="font-semibold text-on-surface">{{ $antrean->pasien->namapasien }}</p>
                <p class="mt-4 text-sm text-on-surface-variant">Keluhan</p>
                <p class="text-on-surface">{{ $antrean->keluhan ?: '-' }}</p>
            </div>
            <div class="rounded-3xl bg-white p-6 border border-outline-variant">
                <h2 class="text-lg font-semibold text-on-surface">Detail Layanan</h2>
                <div class="mt-4 space-y-3 text-sm text-on-surface-variant">
                    <div>
                        <p class="font-semibold text-on-surface">{{ $antrean->jadwal->poli->namapoli }}</p>
                        <p>Poli</p>
                    </div>
                    <div>
                        <p class="font-semibold text-on-surface">{{ $antrean->jadwal->dokter->namadokter }}</p>
                        <p>Dokter</p>
                    </div>
                    <div>
                        <p class="font-semibold text-on-surface">{{ ucfirst($antrean->status) }}</p>
                        <p>Status</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 rounded-3xl bg-primary-container p-6 text-on-primary-container">
            <p class="text-sm font-semibold">Instruksi</p>
            <p class="mt-3 text-sm leading-7">Silakan datang 15 menit sebelum jadwal, dan tunjukkan tiket ini kepada petugas resepsionis. Jaga protokol kesehatan selama kunjungan.</p>
        </div>

        @if($antrean->status === 'menunggu')
            <form action="{{ route('patient.batal-antrean', $antrean->idantrean) }}" method="POST" class="mt-6">
                @csrf
                <button type="submit" class="rounded-2xl bg-error-container px-6 py-3 text-sm font-semibold text-on-error-container hover:brightness-95 transition">Batalkan Antrean</button>
            </form>
        @endif
    </div>
</main>
@endsection
