<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\JadwalDokter;
use App\Models\Antrean;
use App\Models\Notifikasi;
use App\Models\Polis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    public function home()
    {
        $user = Auth::user();
        $activeQueue = Antrean::where('user_id', $user->id)
            ->whereIn('status', ['menunggu', 'dipanggil'])
            ->whereDate('tanggal', today())
            ->with(['poli','doctor.user'])
            ->first();

        $notifications = Notifikasi::where('user_id', $user->id)
            ->latest()->take(5)->get();

        // Get polis with calculated kuota_tersisa
        $polis = Polis::where('is_active', true)
            ->get()
            ->map(function ($p) {
                // Count antreans for this poli today
                $terisi = Antrean::where('poli_id', $p->id)
                    ->whereDate('tanggal', today())
                    ->whereIn('status', ['menunggu','dipanggil'])
                    ->count();
                $p->kuota_tersisa = max(0, $p->kuota_harian - $terisi);
                return $p;
            });

        return view('patient.home', compact('user', 'activeQueue', 'notifications', 'polis'));
    }

    public function ambilAntrean()
    {
        $polis = Polis::where('is_active', true)->get();
        return view('patient.ambil-antrean', compact('polis'));
    }

    public function getDokterByPoli(Request $request)
    {
        $doctors = Doctor::where('poli_id', $request->poli_id)
            ->where('is_active', true)
            ->with('user')
            ->get()
            ->map(fn($d) => [
                'id'           => $d->id,
                'nama'         => $d->user->name,
                'spesialisasi' => $d->spesialisasi,
                'rating'       => $d->rating,
                'total_pasien' => $d->total_pasien,
                'foto'         => $d->foto,
            ]);
        return response()->json($doctors);
    }

    public function getJadwalByDokter(Request $request)
    {
        $jadwals = JadwalDokter::where('doctor_id', $request->doctor_id)
            ->where('is_active', true)
            ->whereDate('tanggal', '>=', today())
            ->where('terisi', '<', DB::raw('kuota'))
            ->orderBy('tanggal')
            ->get()
            ->map(fn($j) => [
                'id'         => $j->id,
                'tanggal'    => $j->tanggal->format('d M Y'),
                'tanggal_raw'=> $j->tanggal->format('Y-m-d'),
                'jam_mulai'  => $j->jam_mulai,
                'jam_selesai'=> $j->jam_selesai,
                'sisa_kuota' => $j->sisaKuota(),
            ]);
        return response()->json($jadwals);
    }

    public function storeAntrean(Request $request)
    {
        $request->validate([
            'poli_id'   => 'required|exists:polis,id',
            'doctor_id' => 'required|exists:doctors,id',
            'jadwal_id' => 'required|exists:jadwal_dokters,id',
            'keluhan'   => 'nullable|string|max:500',
        ]);

        $user   = Auth::user();
        $jadwal = JadwalDokter::findOrFail($request->jadwal_id);
        $poli   = Polis::findOrFail($request->poli_id);

        if ($jadwal->isFull()) {
            return back()->withErrors(['jadwal_id' => 'Maaf, kuota jadwal ini sudah penuh.']);
        }

        // Check if user already has queue today for this poli
        $existing = Antrean::where('user_id', $user->id)
            ->where('poli_id', $request->poli_id)
            ->whereDate('tanggal', $jadwal->tanggal)
            ->whereIn('status', ['menunggu','dipanggil'])
            ->first();
        if ($existing) {
            return back()->withErrors(['poli_id' => 'Anda sudah memiliki antrean aktif di poli ini untuk tanggal tersebut.']);
        }

        $nomor = Antrean::generateNomor($poli->kode, $jadwal->tanggal->format('Y-m-d'));

        $antrean = Antrean::create([
            'user_id'        => $user->id,
            'poli_id'        => $request->poli_id,
            'doctor_id'      => $request->doctor_id,
            'jadwal_id'      => $request->jadwal_id,
            'nomor_antrean'  => $nomor,
            'tanggal'        => $jadwal->tanggal,
            'jam_kedatangan' => $jadwal->jam_mulai,
            'estimasi_layanan'=> date('H:i', strtotime($jadwal->jam_mulai) + (($jadwal->terisi) * 15 * 60)),
            'keluhan'        => $request->keluhan,
            'status'         => 'menunggu',
        ]);

        // Increment terisi
        $jadwal->increment('terisi');

        // Notify patient
        Notifikasi::create([
            'user_id' => $user->id,
            'judul'   => 'Pendaftaran Berhasil',
            'pesan'   => "Nomor antrean {$nomor} di {$poli->nama} berhasil dikonfirmasi.",
            'icon'    => 'check_circle',
        ]);

        return redirect()->route('patient.tiket', $antrean->id);
    }

    public function tiket(Antrean $antrean)
    {
        if ($antrean->user_id !== Auth::id()) abort(403);
        $antrean->load(['poli','doctor.user','jadwal']);
        return view('patient.tiket', compact('antrean'));
    }

    public function batalAntrean(Antrean $antrean)
    {
        if ($antrean->user_id !== Auth::id()) abort(403);
        if (!in_array($antrean->status, ['menunggu'])) {
            return back()->withErrors(['status' => 'Antrean tidak dapat dibatalkan.']);
        }
        $antrean->update(['status' => 'batal']);
        $antrean->jadwal->decrement('terisi');
        return redirect()->route('patient.home')->with('success', 'Antrean berhasil dibatalkan.');
    }

    public function jadwalSaya()
    {
        $antreans = Antrean::where('user_id', Auth::id())
            ->with(['poli','doctor.user'])
            ->latest()
            ->paginate(10);
        return view('patient.jadwal', compact('antreans'));
    }
}
