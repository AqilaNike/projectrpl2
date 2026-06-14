@extends('layouts.app')
@section('title', 'Registrasi Antrean Offline')
@section('content')
@include('layouts.admin-sidebar')
<main class="pt-24 pb-20 px-4 md:px-16 max-w-7xl mx-auto md:ml-64">
    <div class="rounded-3xl bg-surface-container p-6 shadow-sm border border-outline-variant">
        <h1 class="text-2xl font-semibold text-on-surface">Registrasi Offline</h1>
        <p class="mt-2 text-sm text-on-surface-variant">Daftarkan pasien ke antrean yang tersedia hari ini (langsung di tempat).</p>
        
        @if($errors->any())
        <div class="mt-4 p-4 bg-error-container text-on-error-container rounded-xl text-sm font-semibold">
            {{ $errors->first() }}
        </div>
        @endif

        <form action="{{ route('admin.registrasi.store') }}" method="POST" class="mt-8 bg-white p-6 rounded-3xl border border-outline-variant space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-2">Pilih Pasien</label>
                <select name="idpasien" required class="w-full rounded-2xl border border-outline-variant px-4 py-3 bg-surface text-on-surface focus:outline-primary">
                    <option value="">-- Pilih Pasien --</option>
                    @foreach($pasiens as $pasien)
                        <option value="{{ $pasien->idpasien }}">{{ $pasien->nik }} - {{ $pasien->namapasien }}</option>
                    @endforeach
                </select>
                <p class="mt-2 text-xs text-outline">Pilih pasien dari database. (Gunakan Ctrl+F untuk pencarian cepat)</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-on-surface-variant mb-2">Pilih Jadwal Poli (Hari Ini)</label>
                <select name="idjadwal" required class="w-full rounded-2xl border border-outline-variant px-4 py-3 bg-surface text-on-surface focus:outline-primary">
                    <option value="">-- Pilih Jadwal & Dokter --</option>
                    @foreach($jadwals as $jadwal)
                        <option value="{{ $jadwal->idjadwal }}">
                            {{ $jadwal->poli->namapoli }} - {{ $jadwal->dokter->namadokter }} (Sisa: {{ $jadwal->sisaKuota() }} kuota)
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="w-full rounded-2xl bg-primary px-6 py-4 text-sm font-bold text-white hover:bg-primary/90 hover:shadow-md transition-all">
                Daftarkan & Cetak Tiket
            </button>
        </form>
    </div>
</main>
@endsection
