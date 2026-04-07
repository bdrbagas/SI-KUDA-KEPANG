<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\DataStunting;
use App\Models\DesilKemiskinan;
use App\Models\DokumentasiLingkungan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $tahunSekarang = request('tahun', date('Y'));

        $totalDesa = Desa::count();

        // Statistik Stunting
        $totalStunting = DataStunting::whereYear('periode', $tahunSekarang)->sum('jumlah_stunting');
        $totalBalitaDitimbang = DataStunting::whereYear('periode', $tahunSekarang)->sum('jumlah_balita_ditimbang');

        // Statistik Kemiskinan
        $totalKeluargaMiskin = DesilKemiskinan::where('tahun', $tahunSekarang)
            ->selectRaw('SUM(desil_1 + desil_2 + desil_3 + desil_4 + desil_5) as total')
            ->value('total') ?? 0;

        // Statistik Lingkungan
        $totalKegiatan = DokumentasiLingkungan::whereYear('tanggal_kegiatan', $tahunSekarang)->count();

        // Data per desa untuk chart
        $desaStats = Desa::withCount([
            'dataStuntings' => function ($query) use ($tahunSekarang) {
                $query->whereYear('periode', $tahunSekarang);
            },
            'desilKemiskinans' => function ($query) use ($tahunSekarang) {
                $query->where('tahun', $tahunSekarang);
            },
            'dokumentasiLingkungans' => function ($query) use ($tahunSekarang) {
                $query->whereYear('tanggal_kegiatan', $tahunSekarang);
            }
        ])->get();

        return view('dashboard', compact(
            'totalDesa',
            'totalStunting',
            'totalBalitaDitimbang',
            'totalKeluargaMiskin',
            'totalKegiatan',
            'desaStats',
            'tahunSekarang'
        ));
    }
}
