<?php

namespace App\Http\Controllers;

use App\Models\Antrean;
use App\Models\Dokter;
use App\Models\JadwalLayanan;
use App\Models\Notifikasi;
use App\Models\Poli;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        $todayAntreans = Antrean::whereHas('jadwal', fn($q) => $q->where('tanggal', today()));

        $stats = [
            'total_pasien' => (clone $todayAntreans)->count(),
            'menunggu'     => (clone $todayAntreans)->where('status', 'menunggu')->count(),
            'selesai'      => (clone $todayAntreans)->where('status', 'selesai')->count(),
            'batal'        => (clone $todayAntreans)->where('status', 'batal')->count(),
            'avg_wait'     => 14,
        ];

        // Last 7 days chart
        $chartData = collect(range(6, 0))->map(function ($i) {
            $date = now()->subDays($i);
            return [
                'label' => $date->isoFormat('ddd'),
                'count' => Antrean::whereHas('jadwal', fn($q) => $q->where('tanggal', $date->format('Y-m-d')))->count(),
            ];
        });

        $dokters   = Dokter::all();
        $polis     = Poli::all();
        $recentAnt = Antrean::with(['pasien', 'jadwal.poli', 'jadwal.dokter'])
            ->whereHas('jadwal', fn($q) => $q->where('tanggal', today()))
            ->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'chartData', 'dokters', 'polis', 'recentAnt'));
    }

    // ───── Kelola Antrean ─────
    public function antrean(Request $request)
    {
        $query = Antrean::with(['pasien', 'jadwal.poli', 'jadwal.dokter'])
            ->whereHas('jadwal', fn($q) => $q->where('tanggal', today()));

        if ($request->filled('poli_id')) {
            $query->whereHas('jadwal', fn($q) => $q->where('idpoli', $request->poli_id));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->whereHas('pasien', fn($q) => $q->where('namapasien', 'like', "%{$request->search}%")
                ->orWhere('nik', 'like', "%{$request->search}%"));
        }

        $antreans = $query->latest()->paginate(15)->withQueryString();
        $polis    = Poli::all();

        $todayAntreans = Antrean::whereHas('jadwal', fn($q) => $q->where('tanggal', today()));
        $stats = [
            'total'    => (clone $todayAntreans)->count(),
            'menunggu' => (clone $todayAntreans)->where('status', 'menunggu')->count(),
            'selesai'  => (clone $todayAntreans)->where('status', 'selesai')->count(),
            'batal'    => (clone $todayAntreans)->where('status', 'batal')->count(),
        ];

        return view('admin.antrean', compact('antreans', 'polis', 'stats'));
    }

    public function panggilAntrean(Antrean $antrean)
    {
        $antrean->update(['status' => 'dipanggil', 'waktupanggil' => now()]);
        return back()->with('success', 'Antrean berhasil dipanggil.');
    }

    public function selesaikanAntrean(Antrean $antrean)
    {
        $antrean->update(['status' => 'selesai', 'waktuselesai' => now()]);

        Notifikasi::create([
            'idnotifikasi'    => 'NTF' . Str::upper(Str::random(7)),
            'idantrean'       => $antrean->idantrean,
            'jenisnotifikasi' => 'selesai',
            'pesan'           => "Kunjungan Anda di {$antrean->jadwal->poli->namapoli} telah selesai. Terima kasih.",
            'statuskirim'     => 'terkirim',
            'waktukirim'      => now(),
            'nomortujuan'     => $antrean->pasien->nomorhp,
        ]);
        return back()->with('success', 'Antrean berhasil diselesaikan.');
    }

    public function batalkanAntrean(Antrean $antrean)
    {
        $antrean->update(['status' => 'batal']);
        $antrean->jadwal->decrement('kuotaterisi');
        return back()->with('success', 'Antrean berhasil dibatalkan.');
    }

    // ───── Monitor Display ─────
    public function monitor()
    {
        $dipanggil = Antrean::with(['jadwal.poli'])
            ->whereHas('jadwal', fn($q) => $q->where('tanggal', today()))
            ->where('status', 'dipanggil')
            ->latest('waktupanggil')
            ->first();

        $berikutnya = Antrean::with(['jadwal.poli'])
            ->whereHas('jadwal', fn($q) => $q->where('tanggal', today()))
            ->where('status', 'menunggu')
            ->take(3)->get();

        return view('admin.monitor', compact('dipanggil', 'berikutnya'));
    }

    // ───── Kelola Jadwal ─────
    public function jadwal()
    {
        $polis   = Poli::all();
        $dokters = Dokter::all();
        $jadwals = JadwalLayanan::with(['dokter', 'poli'])
            ->whereBetween('tanggal', [today(), today()->addDays(6)])
            ->get();
        return view('admin.jadwal', compact('polis', 'dokters', 'jadwals'));
    }

    public function storeJadwal(Request $request)
    {
        $request->validate([
            'iddokter'      => 'required|exists:dokter,iddokter',
            'idpoli'        => 'required|exists:poli,idpoli',
            'tanggal'       => 'required|date|after_or_equal:today',
            'kuotamaksimal' => 'required|integer|min:1|max:100',
        ]);

        $idjadwal = 'JDW' . Str::upper(Str::random(7));

        JadwalLayanan::create([
            'idjadwal'      => $idjadwal,
            'idpoli'        => $request->idpoli,
            'iddokter'      => $request->iddokter,
            'tanggal'       => $request->tanggal,
            'kuotamaksimal' => $request->kuotamaksimal,
        ]);
        return back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function updateKuotaPoli(Request $request, $idpoli)
    {
        $poli = Poli::findOrFail($idpoli);
        $request->validate(['kuotaharian' => 'required|integer|min:1']);
        $poli->update(['kuotaharian' => $request->kuotaharian]);
        return response()->json(['success' => true, 'kuota' => $poli->kuotaharian]);
    }

    public function togglePoli($idpoli)
    {
        $poli = Poli::findOrFail($idpoli);
        $poli->update(['statusbuka' => $poli->statusbuka === 'buka' ? 'tutup' : 'buka']);
        return response()->json(['success' => true, 'is_active' => $poli->statusbuka === 'buka']);
    }

    // ───── Registrasi Offline ─────
    public function registrasi()
    {
        $pasiens = \App\Models\Pasien::all();
        $polis = \App\Models\Poli::where('statusbuka', 'buka')->get();
        // Get all schedules for today
        $jadwals = \App\Models\JadwalLayanan::with(['dokter', 'poli'])
            ->whereDate('tanggal', today())
            ->where('status', 'aktif')
            ->whereColumn('kuotaterisi', '<', 'kuotamaksimal')
            ->get();
        return view('admin.registrasi', compact('pasiens', 'polis', 'jadwals'));
    }

    public function storeRegistrasi(Request $request)
    {
        $request->validate([
            'idpasien' => 'required|exists:pasien,idpasien',
            'idjadwal' => 'required|exists:jadwallayanan,idjadwal',
        ]);

        $jadwal = JadwalLayanan::findOrFail($request->idjadwal);
        if ($jadwal->isFull()) {
            return back()->withErrors(['idjadwal' => 'Jadwal penuh.']);
        }

        // Cek jika pasien sudah punya antrean aktif di poli tersebut hari ini
        $existing = Antrean::where('idpasien', $request->idpasien)
            ->whereHas('jadwal', fn($q) => $q->where('idpoli', $jadwal->idpoli)->where('tanggal', $jadwal->tanggal))
            ->whereIn('status', ['menunggu', 'dipanggil'])
            ->first();

        if ($existing) {
            return back()->withErrors(['idpasien' => 'Pasien ini sudah memiliki antrean aktif di Poli tersebut.']);
        }

        $idantrean = 'ANT' . Str::upper(Str::random(7));
        $nomor = Antrean::generateNomor($jadwal->idpoli, $jadwal->tanggal->format('Y-m-d'));

        $antrean = Antrean::create([
            'idantrean'      => $idantrean,
            'idpasien'       => $request->idpasien,
            'idjadwal'       => $request->idjadwal,
            'idpetugas'      => auth()->user()->petugas->idpetugas ?? null,
            'nomorantrean'   => $nomor,
            'status'         => 'menunggu',
            'waktudaftar'    => now(),
            'estimasitunggu' => $jadwal->kuotaterisi * 15,
            'jenispasien'    => 'UMUM',
        ]);

        $jadwal->increment('kuotaterisi');

        return redirect()->route('admin.cetak.print', $antrean->idantrean);
    }

    // ───── Cetak Nomor ─────
    public function cetak()
    {
        $antreans = Antrean::with(['pasien', 'jadwal.poli', 'jadwal.dokter'])
            ->whereHas('jadwal', fn($q) => $q->where('tanggal', today()))
            ->latest()
            ->paginate(15);
        return view('admin.cetak', compact('antreans'));
    }

    public function printTiket(Antrean $antrean)
    {
        $antrean->load(['jadwal.poli', 'jadwal.dokter', 'pasien']);
        return view('admin.print-tiket', compact('antrean'));
    }

    // ───── Laporan ─────
    public function laporan(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        $query = Antrean::with(['jadwal.poli'])
            ->whereHas('jadwal', function($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal', [$startDate, $endDate]);
            });

        $totalPasien = (clone $query)->count();
        $totalSelesai = (clone $query)->where('status', 'selesai')->count();
        $totalBatal = (clone $query)->where('status', 'batal')->count();
        
        $perPoli = collect();
        if ($totalPasien > 0) {
            $perPoli = (clone $query)->get()->groupBy('jadwal.poli.namapoli')->map(function ($items) {
                return [
                    'total' => $items->count(),
                    'selesai' => $items->where('status', 'selesai')->count(),
                    'batal' => $items->where('status', 'batal')->count(),
                ];
            });
        }

        return view('admin.laporan', compact('startDate', 'endDate', 'totalPasien', 'totalSelesai', 'totalBatal', 'perPoli'));
    }
}
