<?php

namespace Database\Seeders;

use App\Models\Desa;
use App\Models\DesilKemiskinan;
use Illuminate\Database\Seeder;

class DesilKemiskinan2026Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataDesil2026 = [
            'Pangalengan'   => [517, 681, 855, 822, 1542],
            'Margaluyu'     => [467, 463, 604, 490, 503],
            'Warnasari'     => [724, 507, 542, 433, 439],
            'Sukamanah'     => [839, 1070, 1397, 1370, 802],
            'Lamajang'      => [947, 813, 527, 551, 501],
            'Margamukti'    => [959, 1008, 1287, 1068, 672],
            'Margamulya'    => [890, 996, 1144, 965, 868],
            'Banjarsari'    => [364, 332, 327, 281, 273],
            'Sukaluyu'      => [606, 714, 609, 476, 401],
            'Tribaktimulya' => [442, 387, 322, 301, 308],
            'Pulosari'      => [531, 742, 928, 788, 388],
            'Wanasuka'      => [416, 315, 224, 203, 211],
            'Margamekar'    => [531, 535, 633, 549, 327],
        ];

        $dataDesil2025 = [
            'Lamajang'      => [993, 732, 639, 584, 507],
            'Margaluyu'     => [453, 524, 586, 535, 571],
            'Margamekar'    => [536, 597, 555, 514, 480],
            'Margamukti'    => [911, 993, 1123, 936, 1029],
            'Margamulya'    => [842, 1192, 1120, 995, 914],
            'Pangalengan'   => [572, 843, 1132, 1125, 1247],
            'Pulosari'      => [539, 807, 792, 674, 582],
            'Sukaluyu'      => [611, 736, 643, 519, 444],
            'Sukamanah'     => [759, 1163, 1274, 1168, 1124],
            'Tribaktimulya' => [474, 437, 370, 317, 259],
            'Wanasuka'      => [451, 344, 254, 240, 235],
            'Warnasari'    => [592, 552, 624, 550, 470],
            'Banjarsari'   => [336, 342, 348, 320, 339],
        ];

        $desas = Desa::all();

        foreach ([2025, 2026] as $tahun) {
            $dataDesil = $tahun === 2025 ? $dataDesil2025 : $dataDesil2026;

            foreach ($desas as $desa) {
                if (isset($dataDesil[$desa->nama])) {
                    $d = $dataDesil[$desa->nama];

                    DesilKemiskinan::updateOrCreate(
                        [
                            'desa_id' => $desa->id,
                            'tahun' => $tahun,
                        ],
                        [
                            'desil_1' => $d[0],
                            'desil_2' => $d[1],
                            'desil_3' => $d[2],
                            'desil_4' => $d[3],
                            'desil_5' => $d[4],
                            'total_keluarga' => $d[0] + $d[1] + $d[2] + $d[3] + $d[4],
                        ]
                    );
                }
            }
        }
    }
}
