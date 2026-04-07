<?php

namespace Database\Seeders;

use App\Models\Desa;
use App\Models\DataPengangguran;
use Illuminate\Database\Seeder;

class DataPengangguran2025Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataPengangguran = [
            'Banjarsari'    => 194,
            'Lamajang'      => 38,
            'Margaluyu'     => 34,
            'Margamekar'    => 84,
            'Margamukti'    => 211,
            'Margamulya'    => 74,
            'Pangalengan'   => 217,
            'Pulosari'      => 24,
            'Sukaluyu'      => 22,
            'Sukamanah'     => 115,
            'Tribaktimulya' => 80,
            'Wanasuka'      => 92,
            'Warnasari'     => 18,
        ];

        $desas = Desa::all();

        foreach ($desas as $desa) {
            if (isset($dataPengangguran[$desa->nama])) {
                $jumlah = $dataPengangguran[$desa->nama];

                // Cek data yang sudah ada supaya kolom lain (usia produktif, angkatan kerja) tidak ter-reset
                $existing = DataPengangguran::where('desa_id', $desa->id)->where('tahun', 2025)->first();
                $usia = $existing ? $existing->usia_produktif : 0;
                $angkatan = $existing ? $existing->angkatan_kerja : 0;
                $tingkat = $angkatan > 0 ? ($jumlah / $angkatan) * 100 : 0;

                DataPengangguran::updateOrCreate(
                    [
                        'desa_id' => $desa->id,
                        'tahun' => 2025,
                    ],
                    [
                        'jumlah_pengangguran' => $jumlah,
                        'usia_produktif' => $usia,
                        'angkatan_kerja' => $angkatan,
                        'tingkat_pengangguran' => $tingkat,
                    ]
                );
            }
        }
    }
}
