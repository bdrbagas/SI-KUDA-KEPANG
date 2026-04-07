<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Desa;
use App\Models\AgregatPenduduk;

class AgregatPenduduk2025Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataPenduduk = [
            'BANJARSARI' => ['penduduk' => 6114, 'kk' => 2232, 'balita' => 352, 'ibu_hamil' => 69, 'kek' => 3],
            'LAMAJANG' => ['penduduk' => 12385, 'kk' => 4445, 'balita' => 543, 'ibu_hamil' => 70, 'kek' => 10],
            'MARGALUYU' => ['penduduk' => 9926, 'kk' => 3582, 'balita' => 617, 'ibu_hamil' => 179, 'kek' => 3],
            'MARGAMEKAR' => ['penduduk' => 10714, 'kk' => 3738, 'balita' => 775, 'ibu_hamil' => 184, 'kek' => 1],
            'MARGAMUKTI' => ['penduduk' => 19110, 'kk' => 6617, 'balita' => 1143, 'ibu_hamil' => 305, 'kek' => 33],
            'MARGAMULYA' => ['penduduk' => 20360, 'kk' => 6876, 'balita' => 808, 'ibu_hamil' => 311, 'kek' => 7],
            'PANGALENGAN' => ['penduduk' => 23522, 'kk' => 8045, 'balita' => 772, 'ibu_hamil' => 418, 'kek' => 4],
            'PULOSARI' => ['penduduk' => 12661, 'kk' => 4397, 'balita' => 928, 'ibu_hamil' => 207, 'kek' => 5],
            'SUKALUYU' => ['penduduk' => 10248, 'kk' => 3619, 'balita' => 661, 'ibu_hamil' => 184, 'kek' => 2],
            'SUKAMANAH' => ['penduduk' => 22556, 'kk' => 7740, 'balita' => 1400, 'ibu_hamil' => 322, 'kek' => 11],
            'TRIBAKTIMULYA' => ['penduduk' => 6564, 'kk' => 2300, 'balita' => 430, 'ibu_hamil' => 59, 'kek' => 10],
            'WANASUKA' => ['penduduk' => 4883, 'kk' => 1767, 'balita' => 301, 'ibu_hamil' => 69, 'kek' => 0],
            'WARNASARI' => ['penduduk' => 10264, 'kk' => 3662, 'balita' => 666, 'ibu_hamil' => 138, 'kek' => 2],
        ];

        foreach ($dataPenduduk as $kode => $data) {
            $desa = Desa::where('kode', $kode)->first();
            
            if ($desa) {
                $lakiLaki = (int) round($data['penduduk'] / 2);
                $perempuan = $data['penduduk'] - $lakiLaki;

                AgregatPenduduk::updateOrCreate(
                    [
                        'desa_id' => $desa->id,
                        'tahun' => 2025,
                    ],
                    [
                        'jumlah_penduduk' => $data['penduduk'],
                        'jumlah_kk' => $data['kk'],
                        'balita' => $data['balita'],
                        'ibu_hamil' => $data['ibu_hamil'],
                        'laki_laki' => $lakiLaki,
                        'perempuan' => $perempuan,
                    ]
                );

                // Insert/Update data Ibu Hamil KEK per Desa dengan periode Oktober 2025
                $jumlahKek = $data['kek'];
                $persentaseKek = $data['ibu_hamil'] > 0 ? ($jumlahKek / $data['ibu_hamil']) * 100 : 0;

                \App\Models\IbuHamilKek::updateOrCreate(
                    [
                        'desa_id' => $desa->id,
                        'periode' => '2025-10-01',
                    ],
                    [
                        'jumlah_ibu_hamil' => $data['ibu_hamil'],
                        'jumlah_kek' => $jumlahKek,
                        'persentase_kek' => round($persentaseKek, 2),
                        'keterangan' => 'Disamakan dengan data Agregat Penduduk 2025',
                    ]
                );
            }
        }
    }
}
