<?php

namespace Database\Seeders;

use App\Models\Desa;
use App\Models\AnggaranStunting;
use Illuminate\Database\Seeder;

class AnggaranStuntingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Program Pemberian PMT dari Desa (Text from User)
        $dataPmt = [
            'Banjarsari' => 40000000,
            'Lamajang' => 32760000,
            'Margamukti' => 98400000,
            'Margamulya' => 53500000,
            'Warnasari' => 25000000,
            'Pulosari' => 10000000,
            'Sukamanah' => 152600000,
            'Sukaluyu' => 8000000,
            'Pangalengan' => 15000000,
            'Wanasuka' => 17640000,
            'Margaluyu' => 11900000,
            'Margamekar' => 36000000,
        ];

        // 2. Alokasi Anggaran Dana Desa (Total Dana Desa) - From Image
        // Asumsi program ini juga dimasukkan ke anggaran stunting berdasarkan instruksi image.
        // Jika hanya minta sesuai gambar utk desa yang ada:
        $dataTotalImage = [
            'Warnasari' => 158000000,
            'Pulosari' => 659800000,
            'Sukamanah' => 255597000,
            'Sukaluyu' => 183000000,
            'Pangalengan' => 76000000,
        ];

        $desas = Desa::all();

        foreach ($desas as $desa) {
            // Insert PMT
            if (isset($dataPmt[$desa->nama])) {
                AnggaranStunting::updateOrCreate(
                    [
                        'desa_id' => $desa->id,
                        'sumber' => 'desa',
                        'tahun' => 2025,
                        'program' => 'Pemberian PMT dari Desa',
                    ],
                    [
                        'pagu' => $dataPmt[$desa->nama],
                        'realisasi' => $dataPmt[$desa->nama],
                        'persentase_realisasi' => 100.00,
                    ]
                );
            }

            // Insert Total Dana Desa (jika ada instruksi dari image)
            if (isset($dataTotalImage[$desa->nama])) {
                AnggaranStunting::updateOrCreate(
                    [
                        'desa_id' => $desa->id,
                        'sumber' => 'desa',
                        'tahun' => 2025,
                        'program' => 'Alokasi Anggaran Dana Desa (Total)',
                    ],
                    [
                        'pagu' => $dataTotalImage[$desa->nama],
                        'realisasi' => $dataTotalImage[$desa->nama], 
                        'persentase_realisasi' => 100.00,
                    ]
                );
            }
        }
    }
}
