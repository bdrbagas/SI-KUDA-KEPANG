<?php

namespace App\Http\Controllers\Kemiskinan;

use App\Http\Controllers\Controller;
use App\Models\DesilKemiskinan;
use App\Models\DataPengangguran;
use App\Models\RealisasiPkh;
use App\Models\RealisasiSembako;
use App\Models\DataAk1;
use App\Models\DataOjeng;
use App\Models\Desa;
use Illuminate\Http\Request;

class KemiskinanController extends Controller
{
    public function index()
    {
        $tahun = request('tahun', date('Y'));
        $desas = Desa::all();

        // Desil Kemiskinan
        $desilKemiskinan = DesilKemiskinan::with('desa')
            ->where('tahun', $tahun)
            ->get();

        // Data Pengangguran
        $pengangguran = DataPengangguran::with('desa')
            ->where('tahun', $tahun)
            ->get();

        // Summary stats
        $totalMiskin = $desilKemiskinan->sum(function ($d) {
            return $d->desil_1 + $d->desil_2 + $d->desil_3 + $d->desil_4 + $d->desil_5;
        });

        $totalPengangguran = $pengangguran->sum('jumlah_pengangguran');

        return view('kemiskinan.index', compact('desas', 'desilKemiskinan', 'pengangguran', 'tahun', 'totalMiskin', 'totalPengangguran'));
    }

    // ========================
    // DESIL KEMISKINAN
    // ========================
    public function desil()
    {
        $tahun = request('tahun', date('Y'));
        $desas = Desa::all();
        $desilKemiskinan = DesilKemiskinan::with('desa')
            ->where('tahun', $tahun)
            ->orderBy('total_keluarga', 'desc')
            ->get();

        return view('kemiskinan.desil', compact('desas', 'desilKemiskinan', 'tahun'));
    }

    public function storeDesil(Request $request)
    {
        $validated = $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'tahun' => 'required|integer',
            'desil_1' => 'required|integer|min:0',
            'desil_2' => 'required|integer|min:0',
            'desil_3' => 'required|integer|min:0',
            'desil_4' => 'required|integer|min:0',
            'desil_5' => 'nullable|integer|min:0',
            'total_keluarga' => 'nullable|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        DesilKemiskinan::updateOrCreate(
            ['desa_id' => $validated['desa_id'], 'tahun' => $validated['tahun']],
            $validated
        );

        return redirect()->back()->with('success', 'Data desil kemiskinan berhasil disimpan!');
    }

    // ========================
    // PENGANGGURAN
    // ========================
    public function pengangguran()
    {
        $tahun = request('tahun', date('Y'));
        $desas = Desa::all();
        $pengangguran = DataPengangguran::with('desa')
            ->where('tahun', $tahun)
            ->get();

        return view('kemiskinan.pengangguran', compact('desas', 'pengangguran', 'tahun'));
    }

    public function storePengangguran(Request $request)
    {
        $validated = $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'tahun' => 'required|integer',
            'jumlah_pengangguran' => 'required|integer|min:0',
            'usia_produktif' => 'required|integer|min:0',
            'angkatan_kerja' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $validated['tingkat_pengangguran'] = $validated['angkatan_kerja'] > 0
            ? ($validated['jumlah_pengangguran'] / $validated['angkatan_kerja']) * 100
            : 0;

        DataPengangguran::updateOrCreate(
            ['desa_id' => $validated['desa_id'], 'tahun' => $validated['tahun']],
            $validated
        );

        return redirect()->back()->with('success', 'Data pengangguran berhasil disimpan!');
    }

    // ========================
    // PKH
    // ========================
    public function pkh()
    {
        $tahun = request('tahun', date('Y'));
        $desas = Desa::all();
        $realisasiPkh = RealisasiPkh::with('desa')
            ->where('tahun', $tahun)
            ->get();

        return view('kemiskinan.pkh', compact('desas', 'realisasiPkh', 'tahun'));
    }

    public function storePkh(Request $request)
    {
        $validated = $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'tahun' => 'required|integer',
            'jumlah_kpm' => 'required|integer|min:0',
            'anggaran' => 'required|numeric|min:0',
            'realisasi' => 'required|numeric|min:0',
            'periode_penyaluran' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $validated['persentase_realisasi'] = $validated['anggaran'] > 0
            ? ($validated['realisasi'] / $validated['anggaran']) * 100
            : 0;

        RealisasiPkh::create($validated);

        return redirect()->back()->with('success', 'Data realisasi PKH berhasil disimpan!');
    }

    public function updatePkh(Request $request, RealisasiPkh $pkh)
    {
        $validated = $request->validate([
            'jumlah_kpm' => 'required|integer|min:0',
            'anggaran' => 'required|numeric|min:0',
            'realisasi' => 'required|numeric|min:0',
            'periode_penyaluran' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $validated['persentase_realisasi'] = $validated['anggaran'] > 0
            ? ($validated['realisasi'] / $validated['anggaran']) * 100
            : 0;

        $pkh->update($validated);

        return redirect()->back()->with('success', 'Data realisasi PKH berhasil diperbarui!');
    }

    // ========================
    // SEMBAKO
    // ========================
    public function sembako()
    {
        $tahun = request('tahun', date('Y'));
        $desas = Desa::all();
        $realisasiSembako = RealisasiSembako::with('desa')
            ->where('tahun', $tahun)
            ->get();

        return view('kemiskinan.sembako', compact('desas', 'realisasiSembako', 'tahun'));
    }

    public function storeSembako(Request $request)
    {
        $validated = $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'tahun' => 'required|integer',
            'bulan' => 'nullable|string',
            'jumlah_kpm' => 'required|integer|min:0',
            'anggaran' => 'required|numeric|min:0',
            'realisasi' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        RealisasiSembako::create($validated);

        return redirect()->back()->with('success', 'Data realisasi sembako berhasil disimpan!');
    }

    public function updateSembako(Request $request, RealisasiSembako $sembako)
    {
        $validated = $request->validate([
            'bulan' => 'nullable|string',
            'jumlah_kpm' => 'required|integer|min:0',
            'anggaran' => 'required|numeric|min:0',
            'realisasi' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $sembako->update($validated);

        return redirect()->back()->with('success', 'Data realisasi sembako berhasil diperbarui!');
    }

    // ========================
    // AK-1 (Pencari Kerja)
    // ========================
    public function ak1()
    {
        $tahun = request('tahun', date('Y'));
        $desas = Desa::all();
        $dataAk1 = DataAk1::with('desa')
            ->where('tahun', $tahun)
            ->get();

        return view('kemiskinan.ak1', compact('desas', 'dataAk1', 'tahun'));
    }

    public function storeAk1(Request $request)
    {
        $validated = $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'tahun' => 'required|integer',
            'total_pencaker' => 'required|integer|min:0',
            'laki_laki' => 'required|integer|min:0',
            'perempuan' => 'required|integer|min:0',
            'sd_sederajat' => 'nullable|integer|min:0',
            'smp_sederajat' => 'nullable|integer|min:0',
            'sma_sederajat' => 'nullable|integer|min:0',
            'diploma' => 'nullable|integer|min:0',
            'sarjana' => 'nullable|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        DataAk1::updateOrCreate(
            ['desa_id' => $validated['desa_id'], 'tahun' => $validated['tahun']],
            $validated
        );

        return redirect()->back()->with('success', 'Data AK-1 berhasil disimpan!');
    }

    // ========================
    // OJEK PANGKALAN
    // ========================
    public function ojeng()
    {
        $desas = Desa::all();
        $dataOjeng = DataOjeng::with('desa')->get();

        return view('kemiskinan.ojeng', compact('desas', 'dataOjeng'));
    }

    public function storeOjeng(Request $request)
    {
        $validated = $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'nama_pangkalan' => 'required|string|max:255',
            'lokasi' => 'required|string',
            'jumlah_ojek' => 'required|integer|min:0',
            'koordinator' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'keterangan' => 'nullable|string',
        ]);

        DataOjeng::create($validated);

        return redirect()->back()->with('success', 'Data ojek pangkalan berhasil disimpan!');
    }

    public function updateOjeng(Request $request, DataOjeng $ojeng)
    {
        $validated = $request->validate([
            'nama_pangkalan' => 'required|string|max:255',
            'lokasi' => 'required|string',
            'jumlah_ojek' => 'required|integer|min:0',
            'koordinator' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'keterangan' => 'nullable|string',
        ]);

        $ojeng->update($validated);

        return redirect()->back()->with('success', 'Data ojek pangkalan berhasil diperbarui!');
    }

    public function destroyOjeng(DataOjeng $ojeng)
    {
        $ojeng->delete();
        return redirect()->back()->with('success', 'Data ojek pangkalan berhasil dihapus!');
    }
}
