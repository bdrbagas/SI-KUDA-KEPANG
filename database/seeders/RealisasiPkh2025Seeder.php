<?php

namespace Database\Seeders;

use App\Models\Desa;
use App\Models\RealisasiPkh;
use Illuminate\Database\Seeder;

class RealisasiPkh2025Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataPkh = [
            'Banjarsari'    => 324,
            'Lamajang'      => 625,
            'Margaluyu'     => 526,
            'Margamekar'    => 398,
            'Margamukti'    => 926,
            'Margamulya'    => 948,
            'Pangalengan'   => 675,
            'Pulosari'      => 720,
            'Sukaluyu'      => 685,
            'Sukamanah'     => 1016,
            'Tribaktimulya' => 371,
            'Wanasuka'      => 285,
            'Warnasari'     => 556,
        ];

        $desas = Desa::all();

        foreach ($desas as $desa) {
            if (isset($dataPkh[$desa->nama])) {
                $jumlahKpm = $dataPkh[$desa->nama];

                // Jika sudah ada data, jangan timpa anggaran & realisasinya (kalau user sudah sempat isi)
                $existing = RealisasiPkh::where('desa_id', $desa->id)->where('tahun', 2025)->first();
                $anggaran = $existing ? $existing->anggaran : 0;
                $realisasi = $existing ? $existing->realisasi : 0;
                $persentase = $anggaran > 0 ? ($realisasi / $anggaran) * 100 : 0;

                RealisasiPkh::updateOrCreate(
                    [
                        'desa_id' => $desa->id,
                        'tahun' => 2025,
                    ],
                    [
                        'jumlah_kpm' => $jumlahKpm,
                        'anggaran' => $anggaran,
                        'realisasi' => $realisasi,
                        'persentase_realisasi' => $persentase,
                        'periode_penyaluran' => $existing ? $existing->periode_penyaluran : 'Tahap 1',
                    ]
                );
            }
        }
    }
}
