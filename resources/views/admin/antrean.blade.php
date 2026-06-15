@extends('layouts.app')

@section('title', 'Daftar Antrean')

@section('content')
@include('layouts.admin-sidebar')
<main class="flex-1 md:ml-64 pt-8 pb-20 px-4 md:px-8 min-h-screen">
    <div class="rounded-3xl bg-surface-container p-6 shadow-sm border border-outline-variant">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-on-surface">Daftar Antrean</h1>
                <p class="mt-2 text-sm text-on-surface-variant">Semua antrean pasien saat ini.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-primary">Kembali ke Dashboard</a>
        </div>

        <div class="mt-8 overflow-x-auto">
            <table class="min-w-full divide-y divide-outline-variant text-sm">
                <thead class="bg-surface-variant text-on-surface-variant">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Nomor</th>
                        <th class="px-4 py-3 text-left font-semibold">Pasien</th>
                        <th class="px-4 py-3 text-left font-semibold">Poli</th>
                        <th class="px-4 py-3 text-left font-semibold">Dokter</th>
                        <th class="px-4 py-3 text-left font-semibold">Status</th>
                        <th class="px-4 py-3 text-left font-semibold">Tanggal</th>
                        <th class="px-4 py-3 text-left font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant bg-white">
                    @forelse($antreans as $antrean)
                        <tr>
                            <td class="px-4 py-3">{{ $antrean->nomorantrean }}</td>
                            <td class="px-4 py-3">{{ $antrean->pasien->namapasien }}</td>
                            <td class="px-4 py-3">{{ $antrean->jadwal->poli->namapoli }}</td>
                            <td class="px-4 py-3">{{ $antrean->jadwal->dokter->namadokter }}</td>
                            <td class="px-4 py-3">{{ ucfirst($antrean->status) }}</td>
                            <td class="px-4 py-3">{{ $antrean->jadwal->tanggal->format('d M Y') }}</td>
                            <td class="px-4 py-3 space-y-2">
                                @if($antrean->status === 'menunggu')
                                    <form action="{{ route('admin.panggil', $antrean->idantrean) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="rounded-full bg-secondary px-3 py-2 text-xs font-semibold text-white">Panggil</button>
                                    </form>
                                @endif
                                @if($antrean->status !== 'selesai')
                                    <form action="{{ route('admin.selesaikan', $antrean->idantrean) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="rounded-full bg-primary px-3 py-2 text-xs font-semibold text-white">Selesaikan</button>
                                    </form>
                                @endif
                                @if($antrean->status !== 'batal')
                                    <form action="{{ route('admin.batal', $antrean->idantrean) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="rounded-full bg-error-container px-3 py-2 text-xs font-semibold text-on-error-container">Batalkan</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-center text-on-surface-variant" colspan="7">Belum ada antrean yang tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $antreans->links() }}
        </div>
    </div>
</main>
@endsection
