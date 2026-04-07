<?php

namespace App\Http\Controllers\Stunting;

use App\Http\Controllers\Controller;
use App\Models\AgregatPenduduk;
use App\Models\DataStunting;
use App\Models\IbuHamilKek;
use App\Models\Desa;
use Illuminate\Http\Request;

class AgregatController extends Controller
{
    public function index()
    {
        $desas = Desa::with(['agregatPenduduks', 'dataStuntings', 'ibuHamilKeks'])
            ->get();

        $tahunSekarang = request('tahun', date('Y'));
        $bulanSekarang = request('bulan');

        // Data agregat penduduk
        $agregatPenduduk = AgregatPenduduk::where('tahun', $tahunSekarang)
            ->with('desa')
            ->get()
            ->sortBy('desa.nama');

        // Data stunting terbaru
        $dataStunting = DataStunting::with('desa')
            ->whereYear('periode', $tahunSekarang)
            ->when($bulanSekarang, function ($query, $bulan) {
                return $query->whereMonth('periode', $bulan);
            })
            ->orderBy('periode', 'desc')
            ->get()
            ->groupBy('desa_id');

        // Data ibu hamil KEK
        $dataKek = IbuHamilKek::with('desa')
            ->whereYear('periode', $tahunSekarang)
            ->when($bulanSekarang, function ($query, $bulan) {
                return $query->whereMonth('periode', $bulan);
            })
            ->orderBy('periode', 'desc')
            ->get()
            ->groupBy('desa_id');

        // Hitung total stunting dari data BNBA terbaru
        $bnbaStuntingCount = \App\Models\BnbaStunting::whereYear('periode', $tahunSekarang)
            ->when($bulanSekarang, function ($query, $bulan) {
                return $query->whereMonth('periode', $bulan);
            })
            ->selectRaw('desa_id, count(*) as total')
            ->groupBy('desa_id')
            ->pluck('total', 'desa_id');

        // Data GIS untuk Map Stunting per Desa
        $gisData = $desas->map(function($desa) use ($bnbaStuntingCount, $agregatPenduduk) {
            $stunting_count = $bnbaStuntingCount->get($desa->id, 0);
            
            $agregat = $agregatPenduduk->firstWhere('desa_id', $desa->id);
            $balita_count = $agregat ? $agregat->balita : 0;
            
            // Kalkulasi persentase stunting
            $percentage = $balita_count > 0 ? ($stunting_count / $balita_count) * 100 : 0;
            
            // Koordinat perkiraan pusat desa
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

        return view('stunting.agregat', compact('desas', 'agregatPenduduk', 'dataStunting', 'dataKek', 'tahunSekarang', 'bulanSekarang', 'gisData'));
    }

    public function storePenduduk(Request $request)
    {
        $validated = $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'tahun' => 'required|integer|min:2020|max:2030',
            'jumlah_penduduk' => 'required|integer|min:0',
            'jumlah_kk' => 'required|integer|min:0',
            'balita' => 'required|integer|min:0',
            'ibu_hamil' => 'required|integer|min:0',
            'laki_laki' => 'required|integer|min:0',
            'perempuan' => 'required|integer|min:0',
        ]);

        AgregatPenduduk::updateOrCreate(
            ['desa_id' => $validated['desa_id'], 'tahun' => $validated['tahun']],
            $validated
        );

        return redirect()->back()->with('success', 'Data agregat penduduk berhasil disimpan!');
    }

    public function storeStunting(Request $request)
    {
        $validated = $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'periode' => 'required|date',
            'jumlah_balita_ditimbang' => 'required|integer|min:0',
            'jumlah_stunting' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $validated['persentase_stunting'] = $validated['jumlah_balita_ditimbang'] > 0
            ? ($validated['jumlah_stunting'] / $validated['jumlah_balita_ditimbang']) * 100
            : 0;

        DataStunting::create($validated);

        return redirect()->back()->with('success', 'Data stunting berhasil disimpan!');
    }

    public function storeKek(Request $request)
    {
        $validated = $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'periode' => 'required|date',
            'jumlah_ibu_hamil' => 'required|integer|min:0',
            'jumlah_kek' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $validated['persentase_kek'] = $validated['jumlah_ibu_hamil'] > 0
            ? ($validated['jumlah_kek'] / $validated['jumlah_ibu_hamil']) * 100
            : 0;

        IbuHamilKek::create($validated);

        return redirect()->back()->with('success', 'Data Ibu Hamil KEK berhasil disimpan!');
    }
}
