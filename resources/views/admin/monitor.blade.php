@extends('layouts.app')

@section('title', 'Monitor Antrean')

@section('content')
@include('layouts.admin-sidebar')
<main class="flex-1 md:ml-64 pt-8 pb-20 px-4 md:px-8 min-h-screen">
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
                        <p class="text-4xl font-bold text-primary">{{ $dipanggil->nomorantrean }}</p>
                        <p class="text-sm text-on-surface">Pasien: {{ $dipanggil->pasien?->namapasien ?? '-' }}</p>
                        <p class="text-sm text-on-surface">Poli: {{ $dipanggil->jadwal?->poli?->namapoli ?? '-' }}</p>
                        <p class="text-sm text-on-surface">Dokter: {{ $dipanggil->jadwal?->dokter?->namadokter ?? '-' }}</p>
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
                                <p class="font-semibold text-on-surface">{{ $item->nomorantrean }} — {{ $item->pasien?->namapasien ?? '-' }}</p>
                                <p class="text-sm text-on-surface-variant">{{ $item->jadwal?->poli?->namapoli ?? '-' }} • {{ $item->jadwal?->tanggal?->format('d M Y') ?? '-' }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="mt-4 text-sm text-on-surface-variant">Tidak ada antrean berikutnya.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Full-screen monitor display --}}
    <div class="fixed inset-0 z-40 bg-surface/80 backdrop-blur-md overflow-hidden hidden lg:flex flex-col md:ml-64">
        {{-- Header --}}
        <header class="bg-surface/80 backdrop-blur-md shadow-sm border-b border-outline-variant/30 flex justify-between items-center w-full px-16 py-4 z-50">
            <div class="flex items-center gap-4">
                <div class="bg-primary p-2 rounded-lg">
                    <span class="material-symbols-outlined text-white" style="font-variation-settings:'FILL' 1">local_hospital</span>
                </div>
                <h1 class="text-2xl font-bold text-primary">Puskesmas Digital</h1>
            </div>
            <div class="flex items-center gap-8">
                <div class="text-right">
                    <p class="text-sm font-semibold text-on-surface-variant" id="current-date">-</p>
                    <p class="text-2xl font-bold text-primary" id="current-time">-</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="text-sm font-bold text-primary hover:underline">← Dashboard</a>
            </div>
        </header>

        {{-- Main Canvas --}}
        <main class="flex-1 flex overflow-hidden p-8 gap-8">
            {{-- Left: Current calling number --}}
            <section class="flex-[3] flex flex-col gap-6">
                <div class="bg-white rounded-[32px] shadow-lg border border-outline-variant flex-1 flex flex-col items-center justify-between p-8 relative overflow-hidden">
                    @if($dipanggil)
                    {{-- Header / Badge --}}
                    <div class="w-full flex justify-start z-10">
                        <div class="bg-primary text-white px-6 py-3 rounded-full flex items-center gap-3"
                             style="animation:pulse-custom 2s infinite ease-in-out">
                            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">volume_up</span>
                            <span class="text-sm font-black uppercase tracking-widest">Sekarang Dipanggil</span>
                        </div>
                    </div>

                    {{-- Main Number --}}
                    <div class="text-center my-auto flex flex-col items-center justify-center z-10 py-4">
                        <h2 class="text-on-surface-variant text-sm font-bold uppercase tracking-[0.2em] mb-4">Nomor Antrean</h2>
                        <div class="text-[100px] xl:text-[180px] leading-none font-black text-primary tracking-tighter drop-shadow-sm">{{ $dipanggil->nomorantrean }}</div>
                        <div class="mt-6 xl:mt-8 flex items-center justify-center gap-4 text-primary bg-primary-container/20 px-8 py-4 xl:px-10 xl:py-5 rounded-2xl border border-primary/10">
                            <span class="material-symbols-outlined text-4xl xl:text-5xl">medical_services</span>
                            <span class="text-3xl xl:text-5xl font-bold leading-none">{{ $dipanggil->jadwal?->poli?->namapoli ?? '-' }}</span>
                        </div>
                    </div>

                    {{-- Footer / Instruction --}}
                    <div class="w-full text-center z-10">
                        <div class="inline-flex items-center gap-3 xl:gap-4 px-6 xl:px-12 py-3 xl:py-4 rounded-full border" style="background-color:rgba(0,108,73,.1);border-color:rgba(0,108,73,.2);color:#006c49">
                            <span class="material-symbols-outlined text-2xl xl:text-3xl" style="font-variation-settings:'FILL' 1">login</span>
                            <span class="text-lg xl:text-2xl font-bold">Silakan Menuju Ruang Periksa</span>
                        </div>
                    </div>
                    @else
                    <div class="text-center opacity-40">
                        <span class="material-symbols-outlined text-[120px] text-outline">hourglass_empty</span>
                        <p class="text-2xl font-bold text-on-surface-variant mt-4">Menunggu Pemanggilan...</p>
                    </div>
                    @endif
                    <div class="absolute -right-20 -bottom-20 opacity-[0.03] pointer-events-none">
                        <span class="material-symbols-outlined text-[400px]">medical_services</span>
                    </div>
                </div>
            </section>

            {{-- Right: Next queues --}}
            <aside class="flex-1 flex flex-col gap-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-bold text-on-surface">Antrean Berikutnya</h3>
                    <span class="text-sm font-bold text-primary bg-primary-container/20 px-3 py-1 rounded-lg">{{ $berikutnya->count() }} Antrean</span>
                </div>

                @forelse($berikutnya as $ant)
                @php $colors=['border-l-primary','border-l-secondary','border-l-tertiary']; $c=$colors[$loop->index % 3]; @endphp
                <div class="bg-white p-4 xl:p-6 rounded-2xl shadow-sm border-l-8 {{ $c }} border border-outline-variant flex items-center justify-between hover:scale-[1.02] transition-all">
                    <div class="min-w-0 pr-4">
                        <p class="text-xs font-bold text-on-surface-variant uppercase truncate">{{ $ant->jadwal?->poli?->namapoli ?? '-' }}</p>
                        <p class="text-4xl xl:text-5xl font-black text-on-surface leading-tight whitespace-nowrap">{{ $ant->nomorantrean }}</p>
                    </div>
                    <div class="bg-surface-container p-3 rounded-xl shrink-0">
                        <span class="material-symbols-outlined text-primary text-2xl xl:text-3xl">medical_services</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-on-surface-variant text-sm opacity-60">Tidak ada antrean menunggu.</div>
                @endforelse

                {{-- Health info --}}
                <div class="bg-primary rounded-2xl p-6 text-white shadow-lg mt-auto">
                    <h4 class="font-bold text-xl mb-2">Informasi Sehat</h4>
                    <p class="text-base opacity-90">Jangan lupa mencuci tangan menggunakan sabun sebelum masuk ruang periksa untuk menjaga kebersihan bersama.</p>
                </div>
            </aside>
        </main>

        {{-- Ticker --}}
        <footer class="bg-inverse-surface py-3 overflow-hidden flex items-center">
            <div class="bg-error px-6 py-1 flex items-center z-10 shadow-xl shrink-0">
                <span class="text-white font-black whitespace-nowrap text-sm">INFO LAYANAN</span>
            </div>
            <div class="whitespace-nowrap overflow-hidden flex-1">
                <span style="display:inline-block;animation:marquee 25s linear infinite;padding-left:100%"
                      class="text-white text-xl font-medium px-4">
                    Selamat datang di Puskesmas Digital. Jam operasional mulai pukul 07.30 - 14.00 WIB. Bagi pasien BPJS, pastikan rujukan dan kartu aktif. Gunakan aplikasi Puskesmas Digital untuk ambil antrean online dari rumah!
                </span>
            </div>
        </footer>
    </div>
</main>

@endsection

@push('styles')
<style>
@keyframes pulse-custom { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.8;transform:scale(1.02)} }
@keyframes marquee { 0%{transform:translateX(0)} 100%{transform:translateX(-200%)} }
</style>
@endpush

@push('scripts')
<script>
function updateTime(){
    const now = new Date();
    document.getElementById('current-time').textContent = now.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit',second:'2-digit',hour12:false});
    document.getElementById('current-date').textContent  = now.toLocaleDateString('id-ID',{weekday:'long',day:'numeric',month:'long',year:'numeric'});
}
setInterval(updateTime, 1000); updateTime();

// Auto-refresh page every 15 seconds to catch new calls
setTimeout(() => location.reload(), 15000);
</script>
@endpush
