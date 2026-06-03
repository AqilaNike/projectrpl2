@extends('layouts.app')

@section('title', 'Ambil Antrean')

@section('content')
@include('layouts.patient-nav')
<main class="pt-24 pb-28 px-4 md:px-16 max-w-6xl mx-auto">
    <div class="rounded-3xl bg-surface-container p-6 shadow-sm border border-outline-variant">
        <h1 class="text-2xl font-semibold text-on-surface">Ambil Antrean</h1>
        <p class="mt-2 text-sm text-on-surface-variant">Pilih poli, dokter, dan jadwal untuk mendaftarkan antrean Anda.</p>

        <form action="{{ route('patient.store-antrean') }}" method="POST" class="mt-8 space-y-6" id="antrean-form">
            @csrf
            <div class="grid gap-6 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="text-sm font-medium">Pilih Poli</label>
                    <select name="poli_id" id="poli-select" class="w-full rounded-2xl border border-outline-variant px-4 py-3">
                        <option value="">Pilih Poli</option>
                        @foreach($polis as $poli)
                            <option value="{{ $poli->id }}">{{ $poli->nama }} ({{ $poli->kode }})</option>
                        @endforeach
                    </select>
                    @error('poli_id')<p class="text-sm text-error mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium">Pilih Dokter</label>
                    <select name="doctor_id" id="doctor-select" class="w-full rounded-2xl border border-outline-variant px-4 py-3" disabled>
                        <option value="">Pilih dokter terlebih dahulu</option>
                    </select>
                    @error('doctor_id')<p class="text-sm text-error mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-medium">Pilih Jadwal</label>
                <select name="jadwal_id" id="jadwal-select" class="w-full rounded-2xl border border-outline-variant px-4 py-3" disabled>
                    <option value="">Pilih dokter terlebih dahulu</option>
                </select>
                @error('jadwal_id')<p class="text-sm text-error mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-2">
                <label class="text-sm font-medium">Keluhan (opsional)</label>
                <textarea name="keluhan" rows="4" class="w-full rounded-2xl border border-outline-variant px-4 py-3" placeholder="Tuliskan keluhan atau alasan kunjungan Anda.">{{ old('keluhan') }}</textarea>
                @error('keluhan')<p class="text-sm text-error mt-1">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="rounded-2xl bg-primary px-6 py-3 text-white font-semibold hover:bg-primary-container transition">Daftar Antrean</button>
        </form>
    </div>
</main>
@endsection

@push('scripts')
<script>
    const poliSelect = document.getElementById('poli-select');
    const doctorSelect = document.getElementById('doctor-select');
    const jadwalSelect = document.getElementById('jadwal-select');

    poliSelect.addEventListener('change', async () => {
        const poliId = poliSelect.value;
        doctorSelect.innerHTML = '<option value="">Sedang memuat...</option>';
        jadwalSelect.innerHTML = '<option value="">Pilih dokter terlebih dahulu</option>';
        jadwalSelect.disabled = true;

        if (!poliId) {
            doctorSelect.innerHTML = '<option value="">Pilih poli terlebih dahulu</option>';
            doctorSelect.disabled = true;
            return;
        }

        doctorSelect.disabled = false;
        const url = '{{ route('patient.api.dokter') }}?poli_id=' + poliId;
        const response = await fetch(url);
        const doctors = await response.json();

        doctorSelect.innerHTML = '<option value="">Pilih Dokter</option>';
        doctors.forEach(doc => {
            const opt = document.createElement('option');
            opt.value = doc.id;
            opt.textContent = `${doc.nama} — ${doc.spesialisasi}`;
            doctorSelect.appendChild(opt);
        });
    });

    doctorSelect.addEventListener('change', async () => {
        const doctorId = doctorSelect.value;
        jadwalSelect.innerHTML = '<option value="">Sedang memuat...</option>';
        if (!doctorId) {
            jadwalSelect.innerHTML = '<option value="">Pilih dokter terlebih dahulu</option>';
            jadwalSelect.disabled = true;
            return;
        }

        jadwalSelect.disabled = false;
        const url = '{{ route('patient.api.jadwal') }}?doctor_id=' + doctorId;
        const response = await fetch(url);
        const jadwals = await response.json();

        jadwalSelect.innerHTML = '<option value="">Pilih Jadwal</option>';
        jadwals.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.id;
            opt.textContent = `${item.tanggal} ${item.jam_mulai} - ${item.jam_selesai} (${item.sisa_kuota} sisa)`;
            jadwalSelect.appendChild(opt);
        });
    });
</script>
@endpush
