@extends('layouts.app')
@section('title', 'Laporan Kunjungan')
@section('content')
@include('layouts.admin-sidebar')
<main class="pt-24 pb-20 px-4 md:px-16 max-w-7xl mx-auto md:ml-64">
    <div class="rounded-3xl bg-surface-container p-6 shadow-sm border border-outline-variant">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-on-surface">Laporan Kunjungan</h1>
                <p class="mt-2 text-sm text-on-surface-variant">Rekapitulasi data pasien berdasarkan rentang tanggal.</p>
            </div>
            <button onclick="window.print()" class="rounded-full bg-secondary px-5 py-2.5 text-sm font-bold text-white flex items-center gap-2 hover:shadow-md transition-all hide-on-print">
                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings:'FILL' 1">print</span> Cetak PDF / Kertas
            </button>
        </div>

        <form method="GET" class="mt-6 flex flex-wrap items-end gap-4 bg-white p-5 rounded-3xl border border-outline-variant hide-on-print">
            <div>
                <label class="block text-xs font-bold text-on-surface-variant mb-2">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="rounded-xl border border-outline-variant px-4 py-2 text-sm focus:outline-primary">
            </div>
            <div>
                <label class="block text-xs font-bold text-on-surface-variant mb-2">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="rounded-xl border border-outline-variant px-4 py-2 text-sm focus:outline-primary">
            </div>
            <button type="submit" class="rounded-xl bg-primary px-6 py-2.5 text-white font-bold text-sm hover:brightness-110 transition">Tampilkan Data</button>
        </form>

        {{-- Print Header --}}
        <div class="hidden print-only mb-8 text-center border-b-2 border-black pb-4">
            <h2 class="text-2xl font-black">LAPORAN KUNJUNGAN PASIEN PUSKESMAS DIGITAL</h2>
            <p class="text-lg mt-1">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
        </div>

        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-3xl border border-outline-variant shadow-sm flex flex-col items-center justify-center print-border">
                <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mb-3 hide-on-print">
                    <span class="material-symbols-outlined text-primary">group</span>
                </div>
                <p class="text-sm font-bold text-outline uppercase tracking-wider">Total Kunjungan</p>
                <p class="text-5xl font-black text-primary mt-2">{{ $totalPasien }}</p>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-outline-variant shadow-sm flex flex-col items-center justify-center print-border">
                <div class="w-12 h-12 bg-secondary/10 rounded-full flex items-center justify-center mb-3 hide-on-print">
                    <span class="material-symbols-outlined text-secondary">check_circle</span>
                </div>
                <p class="text-sm font-bold text-outline uppercase tracking-wider">Selesai Dilayani</p>
                <p class="text-5xl font-black text-secondary mt-2">{{ $totalSelesai }}</p>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-outline-variant shadow-sm flex flex-col items-center justify-center print-border">
                <div class="w-12 h-12 bg-error/10 rounded-full flex items-center justify-center mb-3 hide-on-print">
                    <span class="material-symbols-outlined text-error">cancel</span>
                </div>
                <p class="text-sm font-bold text-outline uppercase tracking-wider">Dibatalkan</p>
                <p class="text-5xl font-black text-error mt-2">{{ $totalBatal }}</p>
            </div>
        </div>

        <div class="mt-8 bg-white rounded-3xl border border-outline-variant overflow-hidden print-border">
            <table class="min-w-full divide-y divide-outline-variant text-sm">
                <thead class="bg-surface-variant text-on-surface-variant">
                    <tr>
                        <th class="px-6 py-4 text-left font-bold uppercase tracking-wider">Poli / Layanan</th>
                        <th class="px-6 py-4 text-center font-bold uppercase tracking-wider">Total Kunjungan</th>
                        <th class="px-6 py-4 text-center font-bold uppercase tracking-wider">Selesai</th>
                        <th class="px-6 py-4 text-center font-bold uppercase tracking-wider">Batal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant bg-white">
                    @forelse($perPoli as $namapoli => $data)
                        <tr class="hover:bg-surface/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-on-surface">{{ $namapoli }}</td>
                            <td class="px-6 py-4 text-center font-semibold text-lg">{{ $data['total'] }}</td>
                            <td class="px-6 py-4 text-center text-secondary font-black text-lg">{{ $data['selesai'] }}</td>
                            <td class="px-6 py-4 text-center text-error font-bold text-lg">{{ $data['batal'] }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-8 text-center text-outline font-medium">Tidak ada data untuk periode tanggal yang dipilih.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>

<style>
@media print {
    body { background: white !important; }
    .hide-on-print { display: none !important; }
    .print-only { display: block !important; }
    main { margin: 0 !important; padding: 0 !important; width: 100% !important; max-width: 100% !important; }
    .bg-surface-container { background: white !important; padding: 0 !important; border: none !important; box-shadow: none !important; }
    .print-border { border: 1px solid #000 !important; border-radius: 0 !important; box-shadow: none !important; margin-bottom: 20px; }
    table { width: 100% !important; border-collapse: collapse; }
    th, td { border: 1px solid #ddd !important; }
}
</style>
@endsection
