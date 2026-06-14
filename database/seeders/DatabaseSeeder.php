<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Pengguna;
use App\Models\Pasien;
use App\Models\Dokter;
use App\Models\Petugas;
use App\Models\KepalaPuskesmas;
use App\Models\Poli;
use App\Models\JadwalLayanan;
use App\Models\Antrean;
use App\Models\Notifikasi;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Admin ───
        Pengguna::create([
            'idpengguna'   => 'USR000000001',
            'email'        => 'admin@puskesmas.id',
            'passwordhash' => Hash::make('password'),
            'role'         => 'admin',
            'statusakun'   => 'aktif',
        ]);

        // ─── Petugas ───
        $ptgPengguna = Pengguna::create([
            'idpengguna'   => 'USR000000007',
            'email'        => 'petugas@puskesmas.id',
            'passwordhash' => Hash::make('password'),
            'role'         => 'petugas',
            'statusakun'   => 'aktif',
        ]);
        $petugas = Petugas::create([
            'idpetugas'   => 'PTG000000001',
            'idpengguna'  => 'USR000000007',
            'namapetugas' => 'Sari Mulyani',
            'nomorinduk'  => '198501012010012001',
            'statusaktif' => 'aktif',
            'shift'       => 'pagi',
        ]);

        // ─── Kepala Puskesmas ───
        $kplPengguna = Pengguna::create([
            'idpengguna'   => 'USR000000008',
            'email'        => 'kepala@puskesmas.id',
            'passwordhash' => Hash::make('password'),
            'role'         => 'kepala',
            'statusakun'   => 'aktif',
        ]);
        KepalaPuskesmas::create([
            'idkepala'        => 'KPL000000001',
            'idpengguna'      => 'USR000000008',
            'namakepala'      => 'dr. Hj. Ratna Dewi',
            'nomorinduk'      => '197801012005012001',
            'periodejabatan'  => '2024-2028',
        ]);

        // ─── Dokter ───
        $drAndiPengguna = Pengguna::create([
            'idpengguna'   => 'USR000000002',
            'email'        => 'andi@puskesmas.id',
            'passwordhash' => Hash::make('password'),
            'role'         => 'dokter',
            'statusakun'   => 'aktif',
        ]);
        $drAndi = Dokter::create([
            'iddokter'    => 'DOK000000001',
            'idpengguna'  => 'USR000000002',
            'namadokter'  => 'dr. Andi Wijaya',
            'jenisdokter' => 'Dokter Umum',
        ]);

        $drgSitiPengguna = Pengguna::create([
            'idpengguna'   => 'USR000000003',
            'email'        => 'siti@puskesmas.id',
            'passwordhash' => Hash::make('password'),
            'role'         => 'dokter',
            'statusakun'   => 'aktif',
        ]);
        $drgSiti = Dokter::create([
            'iddokter'    => 'DOK000000002',
            'idpengguna'  => 'USR000000003',
            'namadokter'  => 'drg. Siti Sarah',
            'jenisdokter' => 'Spesialis Gigi & Mulut',
        ]);

        $drAhmadPengguna = Pengguna::create([
            'idpengguna'   => 'USR000000004',
            'email'        => 'ahmad@puskesmas.id',
            'passwordhash' => Hash::make('password'),
            'role'         => 'dokter',
            'statusakun'   => 'aktif',
        ]);
        $drAhmad = Dokter::create([
            'iddokter'    => 'DOK000000003',
            'idpengguna'  => 'USR000000004',
            'namadokter'  => 'dr. Ahmad Hidayat',
            'jenisdokter' => 'Spesialis Penyakit Dalam',
        ]);

        // ─── Pasien ───
        $patient1Pengguna = Pengguna::create([
            'idpengguna'   => 'USR000000005',
            'email'        => 'budi@gmail.com',
            'passwordhash' => Hash::make('password'),
            'role'         => 'pasien',
            'statusakun'   => 'aktif',
        ]);
        $patient1 = Pasien::create([
            'idpasien'     => 'PSN000000001',
            'idpengguna'   => 'USR000000005',
            'nik'          => '3275082109920001',
            'namapasien'   => 'Budi Santoso',
            'nomorhp'      => '081234567890',
        ]);

        $patients = [$patient1];
        $patientNames = [
            'Siti Nurhaliza', 'Ahmad Zaki', 'Rini Wijaya', 'Bambang Suryanto',
            'Dina Kusuma', 'Eka Putra', 'Fiona Cahya', 'Gilang Ramadhan',
            'Hana Wijaksono', 'Indra Kusuma', 'Jasmine Putri', 'Kiki Rahman',
            'Lina Suyanto', 'Mira Kusuma',
        ];

        foreach ($patientNames as $idx => $name) {
            $usrId = 'USR' . str_pad($idx + 10, 9, '0', STR_PAD_LEFT);
            $psnId = 'PSN' . str_pad($idx + 2, 9, '0', STR_PAD_LEFT);

            Pengguna::create([
                'idpengguna'   => $usrId,
                'email'        => 'patient' . ($idx + 2) . '@gmail.com',
                'passwordhash' => Hash::make('password'),
                'role'         => 'pasien',
                'statusakun'   => 'aktif',
            ]);
            $patients[] = Pasien::create([
                'idpasien'   => $psnId,
                'idpengguna' => $usrId,
                'nik'        => '3275082109920' . str_pad($idx + 2, 3, '0', STR_PAD_LEFT),
                'namapasien' => $name,
                'nomorhp'    => '0812345678' . str_pad($idx + 2, 2, '0', STR_PAD_LEFT),
            ]);
        }

        // ─── Poli ───
        $poliUmum   = Poli::create(['idpoli' => 'POL0000001', 'namapoli' => 'Poli Umum',   'kuotaharian' => 50, 'statusbuka' => 'buka', 'jamoperasional' => '08:00-12:00']);
        $poliGigi   = Poli::create(['idpoli' => 'POL0000002', 'namapoli' => 'Poli Gigi',   'kuotaharian' => 20, 'statusbuka' => 'buka', 'jamoperasional' => '08:00-14:00']);
        $poliKia    = Poli::create(['idpoli' => 'POL0000003', 'namapoli' => 'Poli KIA',    'kuotaharian' => 30, 'statusbuka' => 'tutup', 'jamoperasional' => '08:00-12:00']);
        $poliLansia = Poli::create(['idpoli' => 'POL0000004', 'namapoli' => 'Poli Lansia', 'kuotaharian' => 30, 'statusbuka' => 'buka', 'jamoperasional' => '08:00-12:00']);

        // ─── Jadwal Layanan ───
        $jadwals = [];
        $jadwalIdx = 1;
        for ($i = 0; $i < 7; $i++) {
            $date = now()->addDays($i)->format('Y-m-d');
            if (now()->addDays($i)->dayOfWeek === 0) continue;

            $jadwals[] = JadwalLayanan::create(['idjadwal' => 'JDW' . str_pad($jadwalIdx++, 7, '0', STR_PAD_LEFT), 'iddokter' => $drAndi->iddokter,  'idpoli' => $poliUmum->idpoli, 'tanggal' => $date, 'kuotamaksimal' => 20]);
            $jadwals[] = JadwalLayanan::create(['idjadwal' => 'JDW' . str_pad($jadwalIdx++, 7, '0', STR_PAD_LEFT), 'iddokter' => $drgSiti->iddokter, 'idpoli' => $poliGigi->idpoli, 'tanggal' => $date, 'kuotamaksimal' => 10]);
            $jadwals[] = JadwalLayanan::create(['idjadwal' => 'JDW' . str_pad($jadwalIdx++, 7, '0', STR_PAD_LEFT), 'iddokter' => $drAhmad->iddokter, 'idpoli' => $poliUmum->idpoli, 'tanggal' => $date, 'kuotamaksimal' => 15]);
        }

        // ─── Antrean Kemarin ───
        $statusesYesterday = ['selesai', 'selesai', 'selesai', 'selesai', 'batal'];
        $antreanIdx = 1;
        for ($i = 0; $i < 8; $i++) {
            $yesterday = now()->subDays(1)->format('Y-m-d');
            $patient = $patients[$i % count($patients)];
            $jadwal = $jadwals[$i % count($jadwals)];
            $status = $statusesYesterday[$i % count($statusesYesterday)];

            Antrean::create([
                'idantrean'      => 'ANT' . str_pad($antreanIdx++, 7, '0', STR_PAD_LEFT),
                'idpasien'       => $patient->idpasien,
                'idjadwal'       => $jadwal->idjadwal,
                'idpetugas'      => $petugas->idpetugas,
                'nomorantrean'   => 'A-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'status'         => $status,
                'waktudaftar'    => now()->subDays(1)->setHour(8)->setMinute($i * 5),
                'waktupanggil'   => now()->subDays(1)->setHour(rand(8, 14)),
                'waktuselesai'   => $status === 'selesai' ? now()->subDays(1)->setHour(rand(8, 15)) : null,
                'estimasitunggu' => 15,
                'jenispasien'    => $i % 2 === 0 ? 'UMUM' : 'BPJS',
            ]);
        }

        // ─── Antrean Hari Ini ───
        $today = now()->format('Y-m-d');
        $statuses = ['menunggu', 'menunggu', 'menunggu', 'dipanggil', 'selesai'];
        for ($i = 0; $i < 10; $i++) {
            $patient = $patients[$i % count($patients)];
            $jadwal = $jadwals[$i % count($jadwals)];
            $status = $statuses[$i % count($statuses)];

            Antrean::create([
                'idantrean'      => 'ANT' . str_pad($antreanIdx++, 7, '0', STR_PAD_LEFT),
                'idpasien'       => $patient->idpasien,
                'idjadwal'       => $jadwal->idjadwal,
                'idpetugas'      => $petugas->idpetugas,
                'nomorantrean'   => 'A-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'status'         => $status,
                'waktudaftar'    => now()->setHour(8)->setMinute($i * 5),
                'waktupanggil'   => $status !== 'menunggu' ? now()->setHour(rand(8, 14)) : null,
                'waktuselesai'   => $status === 'selesai' ? now()->setHour(rand(8, 15)) : null,
                'estimasitunggu' => 15 + rand(0, 30),
                'jenispasien'    => $i % 2 === 0 ? 'UMUM' : 'BPJS',
            ]);

            $jadwal->increment('kuotaterisi');
        }

        // ─── Antrean Besok ───
        $tomorrow = now()->addDays(1)->format('Y-m-d');
        for ($i = 0; $i < 6; $i++) {
            $patient = $patients[$i % count($patients)];
            $jadwal = $jadwals[$i % count($jadwals)];

            Antrean::create([
                'idantrean'      => 'ANT' . str_pad($antreanIdx++, 7, '0', STR_PAD_LEFT),
                'idpasien'       => $patient->idpasien,
                'idjadwal'       => $jadwal->idjadwal,
                'nomorantrean'   => 'A-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'status'         => 'menunggu',
                'waktudaftar'    => now()->addDays(1)->setHour(8)->setMinute($i * 5),
                'estimasitunggu' => 20,
                'jenispasien'    => 'UMUM',
            ]);

            $jadwal->increment('kuotaterisi');
        }
    }
}
