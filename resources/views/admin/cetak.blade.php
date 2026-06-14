@extends('layouts.app')
@section('title', 'Cetak Nomor Antrean')
@section('content')
@include('layouts.admin-sidebar')
<main class="pt-24 pb-20 px-4 md:px-16 max-w-7xl mx-auto md:ml-64">
    <div class="rounded-3xl bg-surface-container p-6 shadow-sm border border-outline-variant">
        <h1 class="text-2xl font-semibold text-on-surface">Cetak Nomor Antrean</h1>
        <p class="mt-2 text-sm text-on-surface-variant">Cetak ulang tiket antrean fisik untuk pasien hari ini.</p>
        
        <div class="mt-8 bg-white rounded-3xl border border-outline-variant overflow-hidden">
            <table class="min-w-full divide-y divide-outline-variant text-sm">
                <thead class="bg-surface-variant text-on-surface-variant">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold">Nomor</th>
                        <th class="px-6 py-4 text-left font-semibold">Pasien</th>
                        <th class="px-6 py-4 text-left font-semibold">Poli</th>
                        <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant bg-white">
                    @forelse($antreans as $antrean)
                        <tr class="hover:bg-surface/50 transition-colors">
                            <td class="px-6 py-4 font-black text-primary text-lg">{{ $antrean->nomorantrean }}</td>
                            <td class="px-6 py-4 font-semibold">{{ $antrean->pasien->namapasien }}</td>
                            <td class="px-6 py-4">{{ $antrean->jadwal->poli->namapoli }}</td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.cetak.print', $antrean->idantrean) }}" target="_blank" class="rounded-full bg-secondary px-5 py-2 text-xs font-bold text-white inline-flex items-center gap-1 hover:brightness-110 transition-all shadow-sm">
                                    <span class="material-symbols-outlined text-[16px]" style="font-variation-settings:'FILL' 1">print</span> Cetak
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-8 text-center text-outline">Tidak ada antrean hari ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $antreans->links() }}</div>
    </div>
</main>
@endsection
