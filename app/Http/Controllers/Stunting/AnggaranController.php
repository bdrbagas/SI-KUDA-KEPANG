<?php

namespace App\Http\Controllers\Stunting;

use App\Http\Controllers\Controller;
use App\Models\AnggaranStunting;
use App\Models\Desa;
use Illuminate\Http\Request;

class AnggaranController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $sumber = $request->get('sumber');

        $query = AnggaranStunting::with('desa')
            ->where('tahun', $tahun);

        if ($sumber) {
            $query->where('sumber', $sumber);
        }

        $anggarans = $query->orderBy('desa_id')->get();

        $desas = Desa::all();

        // Summary per sumber
        $summaryPerSumber = AnggaranStunting::where('tahun', $tahun)
            ->selectRaw('sumber, SUM(pagu) as total_pagu, SUM(realisasi) as total_realisasi')
            ->groupBy('sumber')
            ->get();

        return view('stunting.anggaran', compact('anggarans', 'desas', 'tahun', 'sumber', 'summaryPerSumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'sumber' => 'required|in:desa,puskesmas,csr',
            'tahun' => 'required|integer|min:2020|max:2030',
            'program' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'pagu' => 'required|numeric|min:0',
            'realisasi' => 'required|numeric|min:0',
        ]);

        $validated['persentase_realisasi'] = $validated['pagu'] > 0
            ? ($validated['realisasi'] / $validated['pagu']) * 100
            : 0;

        AnggaranStunting::create($validated);

        return redirect()->back()->with('success', 'Anggaran stunting berhasil ditambahkan!');
    }

    public function update(Request $request, AnggaranStunting $anggaran)
    {
        $validated = $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'sumber' => 'required|in:desa,puskesmas,csr',
            'program' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'pagu' => 'required|numeric|min:0',
            'realisasi' => 'required|numeric|min:0',
        ]);

        $validated['persentase_realisasi'] = $validated['pagu'] > 0
            ? ($validated['realisasi'] / $validated['pagu']) * 100
            : 0;

        $anggaran->update($validated);

        return redirect()->back()->with('success', 'Anggaran stunting berhasil diperbarui!');
    }

    public function destroy(AnggaranStunting $anggaran)
    {
        $anggaran->delete();
        return redirect()->back()->with('success', 'Anggaran stunting berhasil dihapus!');
    }
}
