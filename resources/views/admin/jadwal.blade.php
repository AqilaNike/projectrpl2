@extends('layouts.app')

@section('title', 'Jadwal Dokter Admin')

@section('content')
@include('layouts.admin-sidebar')
<main class="flex-1 md:ml-64 pt-8 pb-20 px-4 md:px-8 min-h-screen">
    <div class="rounded-3xl bg-surface-container p-6 shadow-sm border border-outline-variant">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-on-surface">Kelola Jadwal Dokter</h1>
                <p class="mt-2 text-sm text-on-surface-variant">Atur jadwal praktik dan kuota dokter.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="rounded-full bg-primary px-4 py-2 text-sm font-semibold text-white">Dashboard</a>
        </div>

        <div id="admin-toast" class="hidden mt-6 rounded-2xl p-4 text-sm shadow-sm transition-all duration-300">
            <div id="admin-toast-content" class="flex items-start gap-3"></div>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-[1.4fr_1fr]">
            <div class="rounded-3xl bg-white p-6 border border-outline-variant">
                <h2 class="text-lg font-semibold text-on-surface">Tambah Jadwal Baru</h2>
                <form action="{{ route('admin.jadwal.store') }}" method="POST" class="mt-6 space-y-5">
                    @csrf
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-on-surface-variant">Dokter</label>
                            <select name="doctor_id" class="mt-2 w-full rounded-2xl border border-outline-variant px-4 py-3">
                                <option value="">Pilih Dokter</option>
                                @foreach($dokters as $dokter)
                                    <option value="{{ $dokter->iddokter }}">{{ $dokter->namadokter }} ({{ $dokter->jenisdokter }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-on-surface-variant">Poli</label>
                            <select name="poli_id" class="mt-2 w-full rounded-2xl border border-outline-variant px-4 py-3">
                                <option value="">Pilih Poli</option>
                                @foreach($polis as $poli)
                                    <option value="{{ $poli->idpoli }}">{{ $poli->namapoli }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-3">
                        <div>
                            <label class="block text-sm font-medium text-on-surface-variant">Tanggal</label>
                            <input type="date" name="tanggal" class="mt-2 w-full rounded-2xl border border-outline-variant px-4 py-3" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-on-surface-variant">Jam Mulai</label>
                            <input type="time" name="jam_mulai" class="mt-2 w-full rounded-2xl border border-outline-variant px-4 py-3" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-on-surface-variant">Jam Selesai</label>
                            <input type="time" name="jam_selesai" class="mt-2 w-full rounded-2xl border border-outline-variant px-4 py-3" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant">Kuota</label>
                        <input type="number" name="kuota" min="1" class="mt-2 w-full rounded-2xl border border-outline-variant px-4 py-3" placeholder="Jumlah pasien maksimum" />
                    </div>

                    <button type="submit" class="rounded-2xl bg-primary px-6 py-3 text-sm font-semibold text-white hover:bg-primary-container transition">Simpan Jadwal</button>
                </form>
            </div>

            <div class="rounded-3xl bg-white p-6 border border-outline-variant overflow-x-auto">
                <h2 class="text-lg font-semibold text-on-surface">Daftar Jadwal</h2>
                <div class="mt-6 min-w-full">
                    <table class="min-w-full divide-y divide-outline-variant text-sm">
                        <thead class="bg-surface-variant text-on-surface-variant">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold">Dokter</th>
                                <th class="px-4 py-3 text-left font-semibold">Poli</th>
                                <th class="px-4 py-3 text-left font-semibold">Tanggal</th>
                                <th class="px-4 py-3 text-left font-semibold">Jam</th>
                                <th class="px-4 py-3 text-left font-semibold">Kuota</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant bg-white">
                            @forelse($jadwals as $jadwal)
                                <tr>
                                    <td class="px-4 py-3">{{ $jadwal->dokter->namadokter }}</td>
                                    <td class="px-4 py-3">{{ $jadwal->poli->namapoli }}</td>
                                    <td class="px-4 py-3">{{ $jadwal->tanggal->format('d M Y') }}</td>
                                    <td class="px-4 py-3">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                                    <td class="px-4 py-3">{{ $jadwal->kuotamaksimal }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-4 py-6 text-center text-on-surface-variant" colspan="5">Belum ada jadwal dokter.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-8 rounded-3xl bg-white p-6 border border-outline-variant">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-on-surface">Kelola Poli</h2>
                    <p class="text-sm text-on-surface-variant">Perbarui kuota harian dan aktifkan atau nonaktifkan layanan poli.</p>
                </div>
            </div>

            <div class="mt-6 space-y-4">
                @foreach($polis as $poli)
                    <div data-poli-id="{{ $poli->idpoli }}" data-poli-kode="{{ $poli->idpoli }}" class="rounded-3xl bg-surface-container p-4 grid gap-4 md:grid-cols-[1fr_360px] md:items-center">
                        <div>
                            <p class="font-semibold text-on-surface">{{ $poli->namapoli }}</p>
                            <p class="text-sm text-on-surface-variant">
                                <span class="poli-kuota-text">ID {{ $poli->idpoli }} • Kuota harian {{ $poli->kuotaharian }}</span>
                                • <span class="poli-status-text">{{ ($poli->statusbuka === 'buka') ? 'Aktif' : 'Nonaktif' }}</span>
                            </p>
                        </div>
                        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-end">
                            <form action="{{ route('admin.poli.kuota', $poli->idpoli) }}" method="POST" class="flex gap-2">
                                @csrf
                                <input type="number" name="kuota_harian" value="{{ $poli->kuotaharian }}" min="1" class="w-24 rounded-2xl border border-outline-variant px-3 py-2" />
                                <button type="submit" class="rounded-2xl bg-primary px-4 py-2 text-white text-sm font-semibold whitespace-nowrap">Simpan</button>
                            </form>
                            <form action="{{ route('admin.poli.toggle', $poli->idpoli) }}" method="POST">
                                @csrf
                                <button type="submit" class="rounded-2xl min-w-[120px] {{ ($poli->statusbuka === 'buka') ? 'bg-error-container text-on-error-container' : 'bg-secondary text-white' }} px-4 py-2 text-sm font-semibold">
                                    {{ ($poli->statusbuka === 'buka') ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</main>
<script>
    function showAdminToast(message, type = 'success') {
        const toast = document.getElementById('admin-toast');
        const toastContent = document.getElementById('admin-toast-content');
        if (!toast || !toastContent) return;

        const colorMap = {
            success: {
                bg: 'bg-[#d1e7dd]',
                border: 'border-[#badbcc]',
                text: 'text-[#0f5132]',
                icon: 'check_circle'
            },
            error: {
                bg: 'bg-[#f8d7da]',
                border: 'border-[#f5c2c7]',
                text: 'text-[#842029]',
                icon: 'error'
            }
        };

        const style = colorMap[type] || colorMap.success;

        toast.className = `mt-6 rounded-2xl border p-4 text-sm shadow-sm transition-all duration-300 ${style.bg} ${style.border} ${style.text}`;
        toastContent.innerHTML = `
            <span class="material-symbols-outlined text-lg">${style.icon}</span>
            <div>${message}</div>
        `;
        toast.classList.remove('hidden');

        window.clearTimeout(window.adminToastTimer);
        window.adminToastTimer = window.setTimeout(() => {
            toast.classList.add('hidden');
        }, 3500);
    }

    document.addEventListener('DOMContentLoaded', () => {
        const forms = document.querySelectorAll('form[action*="poli/"]');

        forms.forEach(form => {
            form.addEventListener('submit', async event => {
                event.preventDefault();
                const action = form.getAttribute('action');
                const method = form.getAttribute('method') || 'POST';
                const formData = new FormData(form);

                try {
                    const response = await fetch(action, {
                        method: method.toUpperCase(),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: formData,
                    });

                    if (!response.ok) {
                        const errorData = await response.json().catch(() => null);
                        throw new Error(errorData?.message || 'Terjadi kesalahan saat memproses permintaan.');
                    }

                    const data = await response.json();

                    if (action.includes('/kuota')) {
                        const parent = form.closest('[data-poli-id]');
                        if (parent) {
                            const kuotaText = parent.querySelector('.poli-kuota-text');
                            if (kuotaText) {
                                kuotaText.textContent = `ID ${parent.dataset.poliKode} • Kuota harian ${data.kuota}`;
                            }
                        }
                        showAdminToast('Kuota harian disimpan', 'success');
                    }

                    if (action.includes('/toggle')) {
                        const button = form.querySelector('button[type="submit"]');
                        const parent = form.closest('[data-poli-id]');
                        if (button && parent) {
                            const isActive = data.is_active;
                            button.textContent = isActive ? 'Nonaktifkan' : 'Aktifkan';
                            button.classList.toggle('bg-error-container', isActive);
                            button.classList.toggle('text-on-error-container', isActive);
                            button.classList.toggle('bg-secondary', !isActive);
                            button.classList.toggle('text-white', !isActive);

                            const statusText = parent.querySelector('.poli-status-text');
                            if (statusText) {
                                statusText.textContent = isActive ? 'Aktif' : 'Nonaktif';
                            }
                        }
                        showAdminToast(`Poli berhasil ${data.is_active ? 'diaktifkan' : 'dinonaktifkan'}`, 'success');
                    }
                } catch (error) {
                    showAdminToast(error.message || 'Gagal menyimpan perubahan.', 'error');
            });
        });
    });
</script>
@endsection
