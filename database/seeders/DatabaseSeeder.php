<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Polis;
use App\Models\Doctor;
use App\Models\JadwalDokter;

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
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'nik' => '3275082109920001',
            'no_hp' => '081234567890',
            'password' => Hash::make('password'),
            'role' => 'patient',
        ]);

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
        for ($i = 0; $i < 7; $i++) {
            $date = now()->addDays($i)->format('Y-m-d');
            // Skip Sunday (0)
            if (now()->addDays($i)->dayOfWeek === 0) continue;

            JadwalDokter::create(['doctor_id' => $doc1->id, 'poli_id' => $poliUmum->id, 'tanggal' => $date, 'jam_mulai' => '08:00', 'jam_selesai' => '12:00', 'kuota' => 20]);
            JadwalDokter::create(['doctor_id' => $doc2->id, 'poli_id' => $poliGigi->id, 'tanggal' => $date, 'jam_mulai' => '08:00', 'jam_selesai' => '14:00', 'kuota' => 10]);
            JadwalDokter::create(['doctor_id' => $doc3->id, 'poli_id' => $poliUmum->id, 'tanggal' => $date, 'jam_mulai' => '13:00', 'jam_selesai' => '17:00', 'kuota' => 15]);
        }
    }
}
