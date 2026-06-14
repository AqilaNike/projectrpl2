@extends('layouts.app')
@section('title', 'Dashboard Admin - Puskesmas Digital')
@section('body-class', 'flex min-h-screen')

@section('content')
@include('layouts.admin-sidebar')

<main class="flex-1 md:ml-64 min-h-screen">
    {{-- TopNav --}}
    <nav class="hidden md:flex justify-between items-center w-full px-8 py-3 bg-surface border-b border-outline-variant/30 sticky top-0 z-30 shadow-sm">
        <div class="flex items-center gap-3">
            <h1 class="text-2xl font-bold text-on-surface">Dashboard</h1>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sm text-on-surface-variant">{{ now()->translatedFormat('l, d F Y') }}</span>
            <a href="{{ route('admin.monitor') }}" class="px-4 py-2 bg-primary text-white rounded-xl text-sm font-bold hover:bg-primary/90 transition-all">
                Monitor Antrean
            </a>
        </div>
    </nav>

    <div class="p-4 md:p-8 space-y-8">
        {{-- Stats Grid --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            @php
                $statsCards = [
                    ['icon'=>'group',           'label'=>'Total Pasien',       'value'=>$stats['total_pasien'], 'border'=>'border-primary',   'bg'=>'bg-primary/10',   'color'=>'text-primary',    'badge'=>'+12%','badge_color'=>'text-secondary'],
                    ['icon'=>'pending_actions', 'label'=>'Antrean Menunggu',   'value'=>$stats['menunggu'],     'border'=>'border-amber-400', 'bg'=>'bg-amber-50',     'color'=>'text-amber-500',  'badge'=>'Menunggu','badge_color'=>'text-amber-700'],
                    ['icon'=>'check_circle',    'label'=>'Selesai',            'value'=>$stats['selesai'],      'border'=>'border-secondary', 'bg'=>'bg-secondary/10', 'color'=>'text-secondary',  'badge'=>'Selesai','badge_color'=>'text-secondary'],
                    ['icon'=>'timer',           'label'=>'Rata-rata Tunggu',   'value'=>$stats['avg_wait'].' mnt','border'=>'border-primary-container','bg'=>'bg-primary/5','color'=>'text-primary','badge'=>'Efisien','badge_color'=>'text-outline'],
                ];
            @endphp
            @foreach($statsCards as $card)
            <div class="bg-white p-5 rounded-xl shadow-sm border-l-4 {{ $card['border'] }} hover:shadow-md transition-all">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-2 rounded-lg {{ $card['bg'] }} {{ $card['color'] }}">
                        <span class="material-symbols-outlined">{{ $card['icon'] }}</span>
                    </div>
                    <span class="text-xs font-semibold {{ $card['badge_color'] }}">{{ $card['badge'] }}</span>
                </div>
                <p class="text-sm text-on-surface-variant font-semibold">{{ $card['label'] }}</p>
                <h2 class="text-4xl font-black text-on-surface leading-none mt-1">{{ $card['value'] }}</h2>
            </div>
            @endforeach
        </div>

        {{-- Chart + Sidebar --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Bar Chart --}}
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-outline-variant/30">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-on-surface">Kunjungan Harian</h3>
                        <p class="text-sm text-on-surface-variant">Statistik pasien 7 hari terakhir</p>
                    </div>
                </div>
                @php $maxCount = max($chartData->pluck('count')->toArray() ?: [1]); @endphp
                <div class="h-56 flex items-end justify-between gap-3">
                    @foreach($chartData as $day)
                    @php $pct = $maxCount > 0 ? max(8, round(($day['count']/$maxCount)*100)) : 8; @endphp
                    <div class="flex-1 h-full flex flex-col justify-end items-center gap-2">
                        <span class="text-xs font-bold text-on-surface-variant">{{ $day['count'] }}</span>
                        <div class="w-full rounded-t-lg transition-all hover:brightness-110"
                             style="height:{{ $pct }}%; background-color:{{ $day['label'] === now()->isoFormat('ddd') ? '#004ac6' : '#d3e4fe' }}"></div>
                        <span class="text-xs {{ $day['label'] === now()->isoFormat('ddd') ? 'font-bold text-primary' : 'text-outline' }}">{{ $day['label'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Doctors on duty --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-outline-variant/30">
                <h3 class="text-sm font-bold text-on-surface mb-5 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[18px]">stethoscope</span> Dokter Bertugas
                </h3>
                <div class="space-y-4">
                    @foreach($dokters->take(4) as $doc)
                    <div class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-on-primary-container text-lg">account_circle</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-on-surface">{{ $doc->namadokter }}</p>
                            <p class="text-xs text-on-surface-variant">{{ $doc->jenisdokter }} • <span class="text-secondary font-medium">Aktif</span></p>
                        </div>
                    </div>
                    @endforeach
                </div>
                <a href="{{ route('admin.jadwal') }}"
                   class="block w-full mt-4 text-sm font-bold text-primary py-3 rounded-xl border border-primary/20 hover:bg-primary-container/10 transition-colors text-center">
                    Lihat Semua Jadwal
                </a>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="relative z-10">
            <h3 class="text-2xl font-bold text-on-surface mb-4">Tindakan Cepat</h3>
            <div class="grid grid-cols-3 md:grid-cols-6 gap-4">
                @foreach([
                    ['url'=>route('admin.antrean'),'icon'=>'group','label'=>'Data Antrean'],
                    ['url'=>route('admin.jadwal'),'icon'=>'event_note','label'=>'Jadwal'],
                    ['url'=>route('admin.monitor'),'icon'=>'monitoring','label'=>'Monitor'],
                    ['url'=>route('admin.registrasi'),'icon'=>'person_add','label'=>'Registrasi'],
                    ['url'=>route('admin.cetak'),'icon'=>'confirmation_number','label'=>'Cetak Nomor'],
                    ['url'=>route('admin.laporan'),'icon'=>'bar_chart_4_bars','label'=>'Laporan'],
                ] as $action)
                <a href="{{ $action['url'] }}"
                   class="flex flex-col items-center justify-center p-5 bg-white rounded-2xl shadow-sm border border-outline-variant/30 hover:border-primary hover:text-primary transition-all group text-on-surface-variant">
                    <span class="material-symbols-outlined mb-2 text-3xl group-hover:scale-110 transition-transform">{{ $action['icon'] }}</span>
                    <span class="text-xs font-semibold text-center">{{ $action['label'] }}</span>
                </a>
                @endforeach
            </div>
        </div>

        {{-- Recent Queue Table --}}
        <div class="bg-white rounded-2xl border border-outline-variant/30 shadow-sm overflow-hidden">
            <div class="flex justify-between items-center p-6 border-b border-outline-variant/20">
                <h3 class="text-xl font-bold text-on-surface">Antrean Terbaru Hari Ini</h3>
                <a href="{{ route('admin.antrean') }}" class="text-sm font-bold text-primary hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-surface-container-low/50 border-b border-outline-variant/30">
                        <tr>
                            <th class="px-5 py-4 text-sm font-semibold text-on-surface-variant">No Antrean</th>
                            <th class="px-5 py-4 text-sm font-semibold text-on-surface-variant">Pasien</th>
                            <th class="px-5 py-4 text-sm font-semibold text-on-surface-variant">Poli</th>
                            <th class="px-5 py-4 text-sm font-semibold text-on-surface-variant">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/20">
                        @forelse($recentAnt as $ant)
                        <tr class="hover:bg-primary-container/5 transition-colors">
                            <td class="px-5 py-4 text-xl font-black text-primary">{{ $ant->nomorantrean }}</td>
                            <td class="px-5 py-4"><span class="font-bold text-on-surface text-sm">{{ $ant->pasien->namapasien }}</span></td>
                            <td class="px-5 py-4 text-sm text-on-surface">{{ $ant->jadwal->poli->namapoli }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold {{ $ant->statusBadgeClass() }}">
                                    {{ strtoupper($ant->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-8 text-on-surface-variant text-sm">Belum ada antrean hari ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection
