<?php

namespace Database\Seeders;

use App\Models\Desa;
use App\Models\DataAk1;
use Illuminate\Database\Seeder;

class DataAk1Seeder extends Seeder
{
    public function run(): void
    {
        $csvPath = base_path('Rekapitulasi Data AK1 Kec. Pangalengan 2025.csv');
        
        if (!file_exists($csvPath)) {
            $this->command->error("File CSV tidak ditemukan: " . $csvPath);
            return;
        }

        $desas = Desa::all()->keyBy(function($item) {
            return strtoupper($item->nama);
        });

        // Struktur untuk menampung agregat
        $rekapPerDesa = [];

        if (($handle = fopen($csvPath, "r")) !== FALSE) {
            $headerCount = 0;
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Lewati baris kosong atau header
                if ($headerCount < 2) {
                    $headerCount++;
                    continue;
                }

                $jk = trim(strtoupper($data[4] ?? ''));
                $namaDesaStr = trim(strtoupper($data[7] ?? ''));
                $jenjang = trim(strtoupper($data[13] ?? ''));

                if (!$namaDesaStr) {
                    continue;
                }

                if (!isset($rekapPerDesa[$namaDesaStr])) {
                    $rekapPerDesa[$namaDesaStr] = [
                        'total_pencaker' => 0,
                        'laki_laki' => 0,
                        'perempuan' => 0,
                        'sd_sederajat' => 0,
                        'smp_sederajat' => 0,
                        'sma_sederajat' => 0,
                        'diploma' => 0,
                        'sarjana' => 0,
                    ];
                }

                $rekapPerDesa[$namaDesaStr]['total_pencaker']++;

                if ($jk === 'L') {
                    $rekapPerDesa[$namaDesaStr]['laki_laki']++;
                } elseif ($jk === 'P') {
                    $rekapPerDesa[$namaDesaStr]['perempuan']++;
                }

                if (str_contains($jenjang, 'SD')) {
                    $rekapPerDesa[$namaDesaStr]['sd_sederajat']++;
                } elseif (str_contains($jenjang, 'SLTP') || str_contains($jenjang, 'SMP')) {
                    $rekapPerDesa[$namaDesaStr]['smp_sederajat']++;
                } elseif (str_contains($jenjang, 'SLTA') || str_contains($jenjang, 'SMK') || str_contains($jenjang, 'SMA') || str_contains($jenjang, 'SMU')) {
                    $rekapPerDesa[$namaDesaStr]['sma_sederajat']++;
                } elseif (str_contains($jenjang, 'DIPLOMA') || str_contains($jenjang, 'D3') || str_contains($jenjang, 'D4')) {
                    $rekapPerDesa[$namaDesaStr]['diploma']++;
                } elseif (str_contains($jenjang, 'SARJANA') || str_contains($jenjang, 'S1')) {
                    $rekapPerDesa[$namaDesaStr]['sarjana']++;
                }
            }
            fclose($handle);
        }

        foreach ($rekapPerDesa as $desaName => $rekap) {
            $desaModel = $desas->get($desaName);
            if ($desaModel) {
                DataAk1::updateOrCreate(
                    [
                        'desa_id' => $desaModel->id,
                        'tahun' => 2025,
                    ],
                    $rekap
                );
                $this->command->info("Data AK-1 berhasil diimport untuk desa: " . $desaName);
            } else {
                $this->command->warn("Desa tidak ditemukan dalam database: " . $desaName);
            }
        }
    }
}
