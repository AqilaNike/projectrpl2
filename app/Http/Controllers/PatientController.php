<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\JadwalLayanan;
use App\Models\Antrean;
use App\Models\Notifikasi;
use App\Models\Poli;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PatientController extends Controller
{
    public function home()
    {
        $pengguna = Auth::user();
        $pasien   = $pengguna->pasien;

        $activeQueue = null;
        if ($pasien) {
            $activeQueue = Antrean::where('idpasien', $pasien->idpasien)
                ->whereIn('status', ['menunggu', 'dipanggil'])
                ->whereHas('jadwal', fn($q) => $q->where('tanggal', today()))
                ->with(['jadwal.poli', 'jadwal.dokter'])
                ->first();
        }

        $notifications = Notifikasi::whereHas('antrean', function ($q) use ($pasien) {
            if ($pasien) $q->where('idpasien', $pasien->idpasien);
        })->latest()->take(5)->get();

        $polis = Poli::where('statusbuka', 'buka')
            ->get()
            ->map(function ($p) {
                $terisi = Antrean::whereHas('jadwal', function ($q) use ($p) {
                    $q->where('idpoli', $p->idpoli)->where('tanggal', today());
                })->whereIn('status', ['menunggu', 'dipanggil'])->count();
                $p->kuota_tersisa = max(0, $p->kuotaharian - $terisi);
                return $p;
            });

        return view('patient.home', compact('pengguna', 'pasien', 'activeQueue', 'notifications', 'polis'));
    }

    public function ambilAntrean()
    {
        $polis = Poli::where('statusbuka', 'buka')->get();
        return view('patient.ambil-antrean', compact('polis'));
    }

    public function getDokterByPoli(Request $request)
    {
        $dokters = JadwalLayanan::where('idpoli', $request->poli_id)
            ->where('status', 'aktif')
            ->whereDate('tanggal', '>=', today())
            ->with('dokter')
            ->get()
            ->pluck('dokter')
            ->unique('iddokter')
            ->values()
            ->map(fn($d) => [
                'id'           => $d->iddokter,
                'nama'         => $d->namadokter,
                'spesialisasi' => $d->jenisdokter,
            ]);
        return response()->json($dokters);
    }

    public function getJadwalByDokter(Request $request)
    {
        $jadwals = JadwalLayanan::where('iddokter', $request->doctor_id)
            ->where('status', 'aktif')
            ->whereDate('tanggal', '>=', today())
            ->whereColumn('kuotaterisi', '<', 'kuotamaksimal')
            ->orderBy('tanggal')
            ->get()
            ->map(fn($j) => [
                'id'          => $j->idjadwal,
                'tanggal'     => $j->tanggal->format('d M Y'),
                'tanggal_raw' => $j->tanggal->format('Y-m-d'),
                'jam_mulai'   => '08:00',
                'jam_selesai' => '14:00',
                'sisa_kuota'  => $j->sisaKuota(),
            ]);
        return response()->json($jadwals);
    }

    public function storeAntrean(Request $request)
    {
        $request->validate([
            'poli_id'   => 'required|exists:poli,idpoli',
            'doctor_id' => 'required|exists:dokter,iddokter',
            'jadwal_id' => 'required|exists:jadwallayanan,idjadwal',
            'keluhan'   => 'nullable|string|max:500',
        ]);

        $pengguna = Auth::user();
        $pasien   = $pengguna->pasien;
        $jadwal   = JadwalLayanan::findOrFail($request->jadwal_id);

        if ($jadwal->isFull()) {
            return back()->withErrors(['jadwal_id' => 'Maaf, kuota jadwal ini sudah penuh.']);
        }

        // Check existing queue
        $existing = Antrean::where('idpasien', $pasien->idpasien)
            ->whereHas('jadwal', fn($q) => $q->where('idpoli', $request->poli_id)->where('tanggal', $jadwal->tanggal))
            ->whereIn('status', ['menunggu', 'dipanggil'])
            ->first();
        if ($existing) {
            return back()->withErrors(['poli_id' => 'Anda sudah memiliki antrean aktif di poli ini untuk tanggal tersebut.']);
        }

        $idantrean = 'ANT' . Str::upper(Str::random(7));
        $nomor = Antrean::generateNomor($request->poli_id, $jadwal->tanggal->format('Y-m-d'));

        $antrean = Antrean::create([
            'idantrean'      => $idantrean,
            'idpasien'       => $pasien->idpasien,
            'idjadwal'       => $request->jadwal_id,
            'nomorantrean'   => $nomor,
            'status'         => 'menunggu',
            'waktudaftar'    => now(),
            'estimasitunggu' => $jadwal->kuotaterisi * 15,
            'jenispasien'    => 'UMUM',
        ]);

        $jadwal->increment('kuotaterisi');

        Notifikasi::create([
            'idnotifikasi'    => 'NTF' . Str::upper(Str::random(7)),
            'idantrean'       => $idantrean,
            'jenisnotifikasi' => 'pendaftaran',
            'pesan'           => "Nomor antrean {$nomor} di {$jadwal->poli->namapoli} berhasil dikonfirmasi.",
            'statuskirim'     => 'terkirim',
            'waktukirim'      => now(),
            'nomortujuan'     => $pasien->nomorhp,
        ]);

        return redirect()->route('patient.tiket', $antrean->idantrean);
    }

    public function tiket(Antrean $antrean)
    {
        $pengguna = Auth::user();
        if ($antrean->idpasien !== $pengguna->pasien->idpasien) abort(403);
        $antrean->load(['jadwal.poli', 'jadwal.dokter']);
        return view('patient.tiket', compact('antrean'));
    }

    public function batalAntrean(Antrean $antrean)
    {
        $pengguna = Auth::user();
        if ($antrean->idpasien !== $pengguna->pasien->idpasien) abort(403);
        if ($antrean->status !== 'menunggu') {
            return back()->withErrors(['status' => 'Antrean tidak dapat dibatalkan.']);
        }
        $antrean->update(['status' => 'batal']);
        $antrean->jadwal->decrement('kuotaterisi');
        return redirect()->route('patient.home')->with('success', 'Antrean berhasil dibatalkan.');
    }

    public function jadwalSaya()
    {
        $pengguna = Auth::user();
        $pasien   = $pengguna->pasien;
        $antreans = Antrean::where('idpasien', $pasien->idpasien)
            ->with(['jadwal.poli', 'jadwal.dokter'])
            ->latest()
            ->paginate(10);
        return view('patient.jadwal', compact('antreans'));
    }
}
