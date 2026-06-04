<?php

namespace App\Http\Controllers;

use App\Models\Antrean;
use App\Models\Doctor;
use App\Models\JadwalDokter;
use App\Models\Notifikasi;
use App\Models\Polis;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_pasien'   => Antrean::whereDate('tanggal', today())->count(),
            'menunggu'       => Antrean::whereDate('tanggal', today())->where('status','menunggu')->count(),
            'selesai'        => Antrean::whereDate('tanggal', today())->where('status','selesai')->count(),
            'batal'          => Antrean::whereDate('tanggal', today())->whereIn('status',['batal'])->count(),
            'avg_wait'       => 14, // placeholder - can compute from dipanggil_at - created_at
        ];

        // Last 7 days chart
        $chartData = collect(range(6, 0))->map(function ($i) {
            $date = now()->subDays($i);
            return [
                'label' => $date->isoFormat('ddd'),
                'count' => Antrean::whereDate('tanggal', $date)->count(),
            ];
        });

        $doctors   = Doctor::with(['user','poli'])->where('is_active', true)->get();
        $polis     = Polis::all();
        $recentAnt = Antrean::with(['user','poli','doctor.user'])
            ->whereDate('tanggal', today())
            ->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats','chartData','doctors','polis','recentAnt'));
    }

    // ───── Kelola Antrean ─────
    public function antrean(Request $request)
    {
        $query = Antrean::with(['user','poli','doctor.user'])->whereDate('tanggal', today());

        if ($request->filled('poli_id'))  $query->where('poli_id', $request->poli_id);
        if ($request->filled('status'))   $query->where('status', $request->status);
        if ($request->filled('search'))   $query->whereHas('user', fn($q) => $q->where('name','like',"%{$request->search}%")->orWhere('nik','like',"%{$request->search}%"));

        $antreans = $query->latest()->paginate(15)->withQueryString();
        $polis    = Polis::all();

        $stats = [
            'total'    => Antrean::whereDate('tanggal', today())->count(),
            'menunggu' => Antrean::whereDate('tanggal', today())->where('status','menunggu')->count(),
            'selesai'  => Antrean::whereDate('tanggal', today())->where('status','selesai')->count(),
            'batal'    => Antrean::whereDate('tanggal', today())->whereIn('status',['batal'])->count(),
        ];

        return view('admin.antrean', compact('antreans','polis','stats'));
    }

    public function panggilAntrean(Antrean $antrean)
    {
        $antrean->update(['status' => 'dipanggil', 'dipanggil_at' => now()]);
        return response()->json(['success' => true, 'nomor' => $antrean->nomor_antrean]);
    }

    public function selesaikanAntrean(Antrean $antrean)
    {
        $antrean->update(['status' => 'selesai', 'selesai_at' => now()]);
        $antrean->doctor->increment('total_pasien');

        Notifikasi::create([
            'user_id' => $antrean->user_id,
            'judul'   => 'Layanan Selesai',
            'pesan'   => "Kunjungan Anda di {$antrean->poli->nama} telah selesai. Terima kasih.",
            'icon'    => 'check_circle',
        ]);
        return response()->json(['success' => true]);
    }

    public function batalkanAntrean(Antrean $antrean)
    {
        $antrean->update(['status' => 'batal']);
        $antrean->jadwal->decrement('terisi');
        return response()->json(['success' => true]);
    }

    // ───── Monitor Display ─────
    public function monitor()
    {
        $dipanggil = Antrean::with('poli')
            ->whereDate('tanggal', today())
            ->where('status', 'dipanggil')
            ->latest('dipanggil_at')
            ->first();

        $berikutnya = Antrean::with('poli')
            ->whereDate('tanggal', today())
            ->where('status', 'menunggu')
            ->take(3)->get();

        return view('admin.monitor', compact('dipanggil','berikutnya'));
    }

    // ───── Kelola Jadwal ─────
    public function jadwal()
    {
        $polis   = Polis::all();
        $doctors = Doctor::with(['user','poli'])->get();
        $jadwals = JadwalDokter::with(['doctor.user','poli'])
            ->whereBetween('tanggal', [today(), today()->addDays(6)])
            ->get();
        return view('admin.jadwal', compact('polis','doctors','jadwals'));
    }

    public function storeJadwal(Request $request)
    {
        $request->validate([
            'doctor_id'   => 'required|exists:doctors,id',
            'poli_id'     => 'required|exists:polis,id',
            'tanggal'     => 'required|date|after_or_equal:today',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required',
            'kuota'       => 'required|integer|min:1|max:100',
        ]);
        JadwalDokter::create($request->only(['doctor_id','poli_id','tanggal','jam_mulai','jam_selesai','kuota']));
        return back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function updateKuotaPoli(Request $request, Polis $poli)
    {
        $request->validate(['kuota_harian' => 'required|integer|min:1']);
        $poli->update(['kuota_harian' => $request->kuota_harian]);
        return response()->json(['success' => true, 'kuota' => $poli->kuota_harian]);
    }

    public function togglePoli(Polis $poli)
    {
        $poli->update(['is_active' => !$poli->is_active]);
        return response()->json(['success' => true, 'is_active' => $poli->is_active]);
    }
}
