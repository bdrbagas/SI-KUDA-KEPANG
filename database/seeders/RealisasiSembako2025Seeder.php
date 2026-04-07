<?php

namespace Database\Seeders;

use App\Models\Desa;
use App\Models\RealisasiSembako;
use Illuminate\Database\Seeder;

class RealisasiSembako2025Seeder extends Seeder
{
    public function run(): void
    {
        $dataSembako = [
            'Banjarsari'    => 517,
            'Lamajang'      => 1388,
            'Margaluyu'     => 1005,
            'Margamekar'    => 766,
            'Margamukti'    => 1434,
            'Margamulya'    => 1789,
            'Pangalengan'   => 1356,
            'Pulosari'      => 1416,
            'Sukaluyu'      => 1238,
            'Sukamanah'     => 1910,
            'Tribaktimulya' => 614,
            'Wanasuka'      => 495,
            'Warnasari'     => 1066,
        ];

        $desas = Desa::all();

        foreach ($desas as $desa) {
            if (isset($dataSembako[$desa->nama])) {
                $jumlahKpm = $dataSembako[$desa->nama];

                $existing = RealisasiSembako::where('desa_id', $desa->id)->where('tahun', 2025)->first();
                $anggaran = $existing ? $existing->anggaran : 0;
                $realisasi = $existing ? $existing->realisasi : 0;

                RealisasiSembako::updateOrCreate(
                    [
                        'desa_id' => $desa->id,
                        'tahun' => 2025,
                    ],
                    [
                        'jumlah_kpm' => $jumlahKpm,
                        'anggaran' => $anggaran,
                        'realisasi' => $realisasi,
                        'bulan' => $existing ? $existing->bulan : 'Tahap 1',
                    ]
                );
            }
        }
    }
}
