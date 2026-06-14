@extends('layouts.app')

@section('title', 'Jadwal Saya')

@section('content')
@include('layouts.patient-nav')
<main class="pt-24 pb-20 px-4 md:px-16 max-w-6xl mx-auto">
    <div class="rounded-3xl bg-surface-container p-6 shadow-sm border border-outline-variant">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-on-surface">Jadwal Saya</h1>
                <p class="mt-2 text-sm text-on-surface-variant">Lihat antrean dan jadwal kunjungan Anda.</p>
            </div>
            <a href="{{ route('patient.ambil-antrean') }}" class="rounded-full bg-primary px-4 py-2 text-sm font-semibold text-white">Ambil Antrean</a>
        </div>

        <div class="mt-8 space-y-4">
            @forelse($antreans as $antrean)
                <div class="rounded-3xl bg-white p-6 border border-outline-variant">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-sm text-on-surface-variant">Nomor antrean</p>
                            <p class="text-3xl font-bold text-primary">{{ $antrean->nomorantrean }}</p>
                        </div>
                        <div class="rounded-2xl bg-surface-container p-3 text-sm font-semibold text-on-surface">
                            {{ ucfirst($antrean->status) }} • {{ $antrean->jadwal->tanggal->format('d M Y') }}
                        </div>
                    </div>

                    <div class="mt-6 grid gap-4 md:grid-cols-2">
                        <div class="space-y-3 text-sm text-on-surface-variant">
                            <div>
                                <p class="font-semibold text-on-surface">{{ $antrean->jadwal->poli->namapoli }}</p>
                                <p>Poli</p>
                            </div>
                            <div>
                                <p class="font-semibold text-on-surface">{{ $antrean->jadwal->dokter->namadokter }}</p>
                                <p>Dokter</p>
                            </div>
                        </div>
                        <div class="rounded-3xl bg-surface-container p-4 text-sm">
                            <p class="font-semibold text-on-surface">Keluhan</p>
                            <p class="text-on-surface-variant">{{ $antrean->keluhan ?: 'Tidak ada keluhan' }}</p>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div class="text-sm text-on-surface-variant">
                            <p>Jam kedatangan: <span class="font-semibold text-on-surface">{{ $antrean->jam_kedatangan }}</span></p>
                        </div>
                        @if($antrean->status === 'menunggu')
                            <form action="{{ route('patient.batal-antrean', $antrean->idantrean) }}" method="POST">
                                @csrf
                                <button type="submit" class="rounded-2xl bg-error-container px-5 py-3 text-sm font-semibold text-on-error-container hover:brightness-95 transition">Batalkan Antrean</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="rounded-3xl bg-white p-8 border border-outline-variant text-center text-on-surface-variant">
                    Belum ada antrean terdaftar. Silakan ambil antrean untuk melihat jadwal Anda di sini.
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $antreans->links() }}
        </div>
    </div>
</main>
@endsection
