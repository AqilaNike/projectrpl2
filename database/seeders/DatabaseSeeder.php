<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Polis;
use App\Models\Doctor;
use App\Models\JadwalDokter;
use App\Models\Antrean;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        $admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@puskesmas.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'nik' => '3275000000000001',
        ]);

        // Doctors (user accounts)
        $drAndi = User::create([
            'name' => 'dr. Andi Wijaya',
            'email' => 'andi@puskesmas.id',
            'password' => Hash::make('password'),
            'role' => 'doctor',
        ]);
        $drgSiti = User::create([
            'name' => 'drg. Siti Sarah',
            'email' => 'siti@puskesmas.id',
            'password' => Hash::make('password'),
            'role' => 'doctor',
        ]);
        $drAhmad = User::create([
            'name' => 'dr. Ahmad Hidayat',
            'email' => 'ahmad@puskesmas.id',
            'password' => Hash::make('password'),
            'role' => 'doctor',
        ]);

        // Sample patient
        $patient1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'nik' => '3275082109920001',
            'no_hp' => '081234567890',
            'password' => Hash::make('password'),
            'role' => 'patient',
        ]);

        // More sample patients for dummy data
        $patients = [$patient1];
        $patientNames = [
            'Siti Nurhaliza', 'Ahmad Zaki', 'Rini Wijaya', 'Bambang Suryanto',
            'Dina Kusuma', 'Eka Putra', 'Fiona Cahya', 'Gilang Ramadhan',
            'Hana Wijaksono', 'Indra Kusuma', 'Jasmine Putri', 'Kiki Rahman',
            'Lina Suyanto', 'Mira Kusuma'
        ];

        foreach ($patientNames as $idx => $name) {
            $patients[] = User::create([
                'name' => $name,
                'email' => 'patient' . ($idx + 2) . '@gmail.com',
                'nik' => '3275082109920' . str_pad($idx + 2, 3, '0', STR_PAD_LEFT),
                'no_hp' => '0812345678' . str_pad($idx + 2, 2, '0', STR_PAD_LEFT),
                'password' => Hash::make('password'),
                'role' => 'patient',
            ]);
        }

        // Poli
        $poliUmum  = Polis::create(['nama' => 'Poli Umum',  'kode' => 'A', 'lokasi' => 'Lt. 1 Gedung A', 'icon' => 'stethoscope',   'kuota_harian' => 50]);
        $poliGigi  = Polis::create(['nama' => 'Poli Gigi',  'kode' => 'G', 'lokasi' => 'Lt. 2 Gedung B', 'icon' => 'dentistry',     'kuota_harian' => 20]);
        $poliKia   = Polis::create(['nama' => 'Poli KIA',   'kode' => 'K', 'lokasi' => 'Lt. 1 Gedung B', 'icon' => 'child_care',    'kuota_harian' => 30, 'is_active' => false]);
        $poliLansia= Polis::create(['nama' => 'Poli Lansia','kode' => 'L', 'lokasi' => 'Lt. 1 Gedung C', 'icon' => 'elderly',       'kuota_harian' => 30]);

        // Doctors linked to poli
        $doc1 = Doctor::create(['user_id' => $drAndi->id,  'poli_id' => $poliUmum->id, 'spesialisasi' => 'Dokter Umum',          'rating' => 4.9, 'total_pasien' => 120]);
        $doc2 = Doctor::create(['user_id' => $drgSiti->id, 'poli_id' => $poliGigi->id, 'spesialisasi' => 'Spesialis Gigi & Mulut','rating' => 4.8, 'total_pasien' => 85]);
        $doc3 = Doctor::create(['user_id' => $drAhmad->id, 'poli_id' => $poliUmum->id, 'spesialisasi' => 'Spesialis Penyakit Dalam','rating' => 4.9,'total_pasien' => 120]);

        // Jadwal for next 7 days
        $jadwals = [];
        for ($i = 0; $i < 7; $i++) {
            $date = now()->addDays($i)->format('Y-m-d');
            // Skip Sunday (0)
            if (now()->addDays($i)->dayOfWeek === 0) continue;

            $jadwals[] = JadwalDokter::create(['doctor_id' => $doc1->id, 'poli_id' => $poliUmum->id, 'tanggal' => $date, 'jam_mulai' => '08:00', 'jam_selesai' => '12:00', 'kuota' => 20]);
            $jadwals[] = JadwalDokter::create(['doctor_id' => $doc2->id, 'poli_id' => $poliGigi->id, 'tanggal' => $date, 'jam_mulai' => '08:00', 'jam_selesai' => '14:00', 'kuota' => 10]);
            $jadwals[] = JadwalDokter::create(['doctor_id' => $doc3->id, 'poli_id' => $poliUmum->id, 'tanggal' => $date, 'jam_mulai' => '13:00', 'jam_selesai' => '17:00', 'kuota' => 15]);
        }

        // Create dummy antrean data for past days (selesai/batal)
        $statusesYesterday = ['selesai', 'selesai', 'selesai', 'selesai', 'batal'];
        for ($i = 0; $i < 8; $i++) {
            $yesterday = now()->subDays(1)->format('Y-m-d');
            $patient = $patients[$i % count($patients)];
            $doctor = $i % 2 === 0 ? $doc1 : $doc2;
            $poli = $doctor->poli;

            Antrean::create([
                'user_id' => $patient->id,
                'poli_id' => $poli->id,
                'doctor_id' => $doctor->id,
                'jadwal_id' => $jadwals[0]->id,
                'nomor_antrean' => $poli->kode . '-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'tanggal' => $yesterday,
                'jam_kedatangan' => '08:' . str_pad($i * 5, 2, '0', STR_PAD_LEFT),
                'estimasi_layanan' => '00:15:00',
                'status' => $statusesYesterday[$i % count($statusesYesterday)],
                'keluhan' => ['Demam & batuk', 'Sakit kepala', 'Nyeri gigi', 'Pemeriksaan rutin', 'Sakit perut'][rand(0, 4)],
                'dipanggil_at' => now()->subDays(1)->setHour(rand(8, 14)),
                'selesai_at' => $statusesYesterday[$i % count($statusesYesterday)] === 'selesai' ? now()->subDays(1)->setHour(rand(8, 15)) : null,
            ]);
        }

        // Create dummy antrean data for today with mixed statuses
        $today = now()->format('Y-m-d');
        $statuses = ['menunggu', 'menunggu', 'menunggu', 'dipanggil', 'selesai'];

        for ($i = 0; $i < 10; $i++) {
            $patient = $patients[$i % count($patients)];
            $doctor = $i % 3 === 0 ? $doc1 : ($i % 3 === 1 ? $doc2 : $doc3);
            $poli = $doctor->poli;
            $status = $statuses[$i % count($statuses)];

            $antrean = Antrean::create([
                'user_id' => $patient->id,
                'poli_id' => $poli->id,
                'doctor_id' => $doctor->id,
                'jadwal_id' => $jadwals[$i % count($jadwals)]->id,
                'nomor_antrean' => $poli->kode . '-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'tanggal' => $today,
                'jam_kedatangan' => '08:' . str_pad($i * 5, 2, '0', STR_PAD_LEFT),
                'estimasi_layanan' => '00:' . str_pad(15 + rand(0, 30), 2, '0', STR_PAD_LEFT) . ':00',
                'status' => $status,
                'keluhan' => ['Demam & batuk', 'Sakit kepala', 'Nyeri gigi', 'Pemeriksaan rutin', 'Sakit perut'][rand(0, 4)],
                'dipanggil_at' => $status !== 'menunggu' ? now()->setHour(rand(8, 14)) : null,
                'selesai_at' => $status === 'selesai' ? now()->setHour(rand(8, 15)) : null,
            ]);

            // Increment jadwal terisi count
            $jadwals[$i % count($jadwals)]->increment('terisi');
        }

        // Create dummy antrean data for tomorrow
        $tomorrow = now()->addDays(1)->format('Y-m-d');
        for ($i = 0; $i < 6; $i++) {
            $patient = $patients[$i % count($patients)];
            $doctor = $i % 2 === 0 ? $doc1 : $doc3;
            $poli = $doctor->poli;

            Antrean::create([
                'user_id' => $patient->id,
                'poli_id' => $poli->id,
                'doctor_id' => $doctor->id,
                'jadwal_id' => $jadwals[$i % count($jadwals)]->id,
                'nomor_antrean' => $poli->kode . '-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'tanggal' => $tomorrow,
                'jam_kedatangan' => '08:' . str_pad($i * 5, 2, '0', STR_PAD_LEFT),
                'estimasi_layanan' => '00:20:00',
                'status' => 'menunggu',
                'keluhan' => ['Demam & batuk', 'Sakit kepala', 'Nyeri gigi'][rand(0, 2)],
            ]);

            // Increment jadwal terisi count
            $jadwals[$i % count($jadwals)]->increment('terisi');
        }
    }
}
