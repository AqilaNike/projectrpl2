<?php

namespace Database\Seeders;

use App\Models\Polis;
use Illuminate\Database\Seeder;

class PolisSeeder extends Seeder
{
    public function run(): void
    {
        $polis = [
            [
                'nama' => 'Poli Umum',
                'kode' => 'U',
                'lokasi' => 'Lt. 1 Gedung A',
                'icon' => 'clinic',
                'is_active' => true,
                'kuota_harian' => 30,
                'jam_buka' => '07:30:00',
                'jam_tutup' => '14:00:00',
            ],
            [
                'nama' => 'Poli Gigi',
                'kode' => 'G',
                'lokasi' => 'Lt. 1 Gedung B',
                'icon' => 'dentistry',
                'is_active' => true,
                'kuota_harian' => 20,
                'jam_buka' => '07:30:00',
                'jam_tutup' => '14:00:00',
            ],
            [
                'nama' => 'Poli KIA',
                'kode' => 'K',
                'lokasi' => 'Lt. 2 Gedung A',
                'icon' => 'favorite',
                'is_active' => true,
                'kuota_harian' => 25,
                'jam_buka' => '07:30:00',
                'jam_tutup' => '14:00:00',
            ],
            [
                'nama' => 'Poli Imunisasi',
                'kode' => 'I',
                'lokasi' => 'Lt. 1 Gedung C',
                'icon' => 'vaccines',
                'is_active' => true,
                'kuota_harian' => 30,
                'jam_buka' => '07:30:00',
                'jam_tutup' => '14:00:00',
            ],
        ];

        foreach ($polis as $poli) {
            Polis::create($poli);
        }
    }
}
