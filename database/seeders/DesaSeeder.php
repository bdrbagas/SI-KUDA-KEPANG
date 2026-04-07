<?php

namespace Database\Seeders;

use App\Models\Desa;
use Illuminate\Database\Seeder;

class DesaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $desas = [
            ['kode' => 'BANJARSARI', 'nama' => 'Banjarsari'],
            ['kode' => 'LAMAJANG', 'nama' => 'Lamajang'],
            ['kode' => 'MARGALUYU', 'nama' => 'Margaluyu'],
            ['kode' => 'MARGAMEKAR', 'nama' => 'Margamekar'],
            ['kode' => 'MARGAMUKTI', 'nama' => 'Margamukti'],
            ['kode' => 'MARGAMULYA', 'nama' => 'Margamulya'],
            ['kode' => 'PANGALENGAN', 'nama' => 'Pangalengan'],
            ['kode' => 'PULOSARI', 'nama' => 'Pulosari'],
            ['kode' => 'SUKALUYU', 'nama' => 'Sukaluyu'],
            ['kode' => 'SUKAMANAH', 'nama' => 'Sukamanah'],
            ['kode' => 'TRIBAKTIMULYA', 'nama' => 'Tribaktimulya'],
            ['kode' => 'WANASUKA', 'nama' => 'Wanasuka'],
            ['kode' => 'WARNASARI', 'nama' => 'Warnasari'],
        ];

        foreach ($desas as $desa) {
            Desa::updateOrCreate(
                ['kode' => $desa['kode']],
                $desa
            );
        }
    }
}
