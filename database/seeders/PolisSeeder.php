<?php

namespace Database\Seeders;

use App\Models\Poli;
use Illuminate\Database\Seeder;

class PolisSeeder extends Seeder
{
    public function run(): void
    {
        $polis = [
            [
                'idpoli' => 'P-UMUM',
                'namapoli' => 'Poli Umum',
                'kuotaharian' => 30,
                'statusbuka' => 'buka',
                'jamoperasional' => '07:30-14:00',
            ],
            [
                'idpoli' => 'P-GIGI',
                'namapoli' => 'Poli Gigi',
                'kuotaharian' => 20,
                'statusbuka' => 'buka',
                'jamoperasional' => '07:30-14:00',
            ],
            [
                'idpoli' => 'P-KIA',
                'namapoli' => 'Poli KIA/KB',
                'kuotaharian' => 25,
                'statusbuka' => 'buka',
                'jamoperasional' => '07:30-14:00',
            ],
            [
                'idpoli' => 'P-MTBS',
                'namapoli' => 'Poli MTBS',
                'kuotaharian' => 20,
                'statusbuka' => 'buka',
                'jamoperasional' => '07:30-14:00',
            ],
            [
                'idpoli' => 'P-GIZI',
                'namapoli' => 'Poli Gizi',
                'kuotaharian' => 15,
                'statusbuka' => 'buka',
                'jamoperasional' => '07:30-14:00',
            ],
            [
                'idpoli' => 'P-KHUSUS',
                'namapoli' => 'Layanan Khusus',
                'kuotaharian' => 15,
                'statusbuka' => 'buka',
                'jamoperasional' => '07:30-14:00',
            ],
            [
                'idpoli' => 'P-TB',
                'namapoli' => 'Poli TB/ISPA',
                'kuotaharian' => 10,
                'statusbuka' => 'buka',
                'jamoperasional' => '07:30-14:00',
            ]
        ];

        foreach ($polis as $poli) {
            Poli::updateOrCreate(['idpoli' => $poli['idpoli']], $poli);
        }
    }
}
