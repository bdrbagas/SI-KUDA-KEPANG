<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\AgregatPenduduk;
use App\Models\DataStunting;
use App\Models\BnbaStunting;
use App\Models\DesilKemiskinan;
use App\Models\DokumentasiLingkungan;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        $tahunSekarang = request('tahun', date('Y'));
        $totalDesa = Desa::count();

        // 1. Statistik Stunting
        $totalBalitaDitimbang = DataStunting::whereYear('periode', $tahunSekarang)->sum('jumlah_balita_ditimbang');
        // Gunakan BnbaStunting untuk total riil jika ada, atau gunakan DataStunting
        $totalStunting = BnbaStunting::whereYear('periode', $tahunSekarang)->count();
        if ($totalStunting === 0) {
            $totalStunting = DataStunting::whereYear('periode', $tahunSekarang)->sum('jumlah_stunting');
        }

        // 2. Statistik Kemiskinan
        $totalKeluargaMiskin = DesilKemiskinan::where('tahun', $tahunSekarang)
            ->selectRaw('SUM(desil_1 + desil_2 + desil_3 + desil_4 + desil_5) as total')
            ->value('total') ?? 0;

        // 3. Statistik Lingkungan
        $totalKegiatan = DokumentasiLingkungan::whereYear('tanggal_kegiatan', $tahunSekarang)->count();

        // Data GIS untuk Map Stunting per Desa
        $desas = Desa::with(['agregatPenduduks'])->get();
        $agregatPenduduk = AgregatPenduduk::where('tahun', $tahunSekarang)->get();

        $bnbaStuntingCount = BnbaStunting::whereYear('periode', $tahunSekarang)
            ->selectRaw('desa_id, count(*) as total')
            ->groupBy('desa_id')
            ->pluck('total', 'desa_id');

        $gisData = $desas->map(function($desa) use ($bnbaStuntingCount, $agregatPenduduk) {
            $stunting_count = $bnbaStuntingCount->get($desa->id, 0);
            
            $agregat = $agregatPenduduk->firstWhere('desa_id', $desa->id);
            $balita_count = $agregat ? $agregat->balita : 0;
            
            $percentage = $balita_count > 0 ? ($stunting_count / $balita_count) * 100 : 0;
            
            $coords = match(strtolower(trim($desa->nama))) {
                'pangalengan' => ['lat' => -7.1818, 'lng' => 107.5682],
                'warnasari' => ['lat' => -7.2223, 'lng' => 107.5925],
                'margamulya' => ['lat' => -7.1706, 'lng' => 107.5752],
                'margamekar' => ['lat' => -7.1952, 'lng' => 107.5714],
                'wanasuka' => ['lat' => -7.2385, 'lng' => 107.5855],
                'banjarsari' => ['lat' => -7.1592, 'lng' => 107.5638],
                'sukamanah' => ['lat' => -7.1565, 'lng' => 107.5501],
                'margaluyu' => ['lat' => -7.1873, 'lng' => 107.5516],
                'pulosari' => ['lat' => -7.2185, 'lng' => 107.5592],
                'tribaktimulya' => ['lat' => -7.1812, 'lng' => 107.5350],
                'lamajang' => ['lat' => -7.1518, 'lng' => 107.5332],
                'margamukti' => ['lat' => -7.2255, 'lng' => 107.5305],
                'sukaluyu' => ['lat' => -7.2415, 'lng' => 107.5301],
                default => ['lat' => -7.190, 'lng' => 107.550]
            };

            return [
                'nama' => $desa->nama,
                'stunting' => $percentage,
                'stunting_count' => $stunting_count,
                'balita_count' => $balita_count,
                'lat' => $coords['lat'],
                'lng' => $coords['lng']
            ];
        });

        return view('public.dashboard', compact(
            'totalDesa',
            'totalStunting',
            'totalBalitaDitimbang',
            'totalKeluargaMiskin',
            'totalKegiatan',
            'tahunSekarang',
            'gisData'
        ));
    }
}
