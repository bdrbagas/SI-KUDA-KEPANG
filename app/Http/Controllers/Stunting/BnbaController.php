<?php

namespace App\Http\Controllers\Stunting;

use App\Http\Controllers\Controller;
use App\Models\BnbaStunting;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BnbaController extends Controller
{
    public function index(Request $request)
    {
        $query = BnbaStunting::with('desa');

        // Filter by tahun dan bulan
        if ($request->filled('tahun')) {
            $query->whereYear('periode', $request->tahun);
        }
        
        if ($request->filled('bulan')) {
            $query->whereMonth('periode', $request->bulan);
        }

        // Filter by desa
        if ($request->filled('desa_id')) {
            $query->where('desa_id', $request->desa_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by nama or NIK
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $bnbas = $query->orderBy('nama')->paginate(25);
        $desas = Desa::all();

        return view('stunting.bnba', compact('bnbas', 'desas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'alamat' => 'required|string',
            'rt_rw' => 'nullable|string|max:20',
            'nama_ibu' => 'nullable|string|max:255',
            'nama_ayah' => 'nullable|string|max:255',
            'posyandu' => 'nullable|string|max:255',
            'berat_badan' => 'nullable|numeric|min:0',
            'tinggi_badan' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
            'bulan' => 'required|string|size:2',
            'tahun' => 'required|integer|min:2020',
        ]);

        $validated['nik'] = 'N' . time() . rand(100, 999);
        $validated['status'] = 'stunting';
        $validated['periode'] = $validated['tahun'] . '-' . $validated['bulan'] . '-01';

        BnbaStunting::create($validated);

        return redirect()->back()->with('success', 'Data BNBA berhasil ditambahkan!');
    }

    public function update(Request $request, BnbaStunting $bnba)
    {
        $validated = $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'alamat' => 'required|string',
            'rt_rw' => 'nullable|string|max:20',
            'nama_ibu' => 'nullable|string|max:255',
            'nama_ayah' => 'nullable|string|max:255',
            'posyandu' => 'nullable|string|max:255',
            'berat_badan' => 'nullable|numeric|min:0',
            'tinggi_badan' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
            'bulan' => 'required|string|size:2',
            'tahun' => 'required|integer|min:2020',
        ]);
        
        $validated['periode'] = $validated['tahun'] . '-' . $validated['bulan'] . '-01';

        $bnba->update($validated);

        return redirect()->back()->with('success', 'Data BNBA berhasil diperbarui!');
    }

    public function destroy(BnbaStunting $bnba)
    {
        $bnba->delete();
        return redirect()->back()->with('success', 'Data BNBA berhasil dihapus!');
    }

    public function importForm()
    {
        $desas = Desa::all();
        return view('stunting.bnba-import', compact('desas'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'tahun' => 'required|integer|min:2020',
            'bulan' => 'required|string|size:2',
            'file' => 'required|mimes:csv,txt|max:10240', // Max 10MB
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), "r");
        
        $header = true;
        $successCount = 0;
        $updateCount = 0;

        while (($row = fgetcsv($handle, 4000, ",")) !== FALSE) {
            // Support semicolon delimited just in case exported from localized Excel
            if (count($row) === 1 && strpos($row[0], ';') !== false) {
                $row = explode(';', $row[0]);
            }

            if ($header) {
                $header = false;
                continue;
            }

            // Nama is required
            if (empty($row[0])) continue; 

            $nama = trim($row[0]);
            
            $existing = BnbaStunting::where('desa_id', $request->desa_id)
                ->where('nama', $nama)
                ->first();

            $tanggalLahir = null;
            if (!empty($row[1])) {
                $time = strtotime(trim($row[1]));
                if ($time !== false) {
                    $tanggalLahir = date('Y-m-d', $time);
                }
            }

            $nik = $existing ? $existing->nik : 'N' . time() . rand(100, 999);

            $data = [
                'desa_id' => $request->desa_id,
                'nik' => $nik,
                'nama' => $nama,
                'tanggal_lahir' => $tanggalLahir,
                'jenis_kelamin' => trim($row[2] ?? ''),
                'alamat' => trim($row[3] ?? ''),
                'rt_rw' => trim($row[4] ?? ''),
                'nama_ibu' => trim($row[5] ?? ''),
                'nama_ayah' => trim($row[6] ?? ''),
                'status' => 'stunting',
                'posyandu' => trim($row[7] ?? ''),
                'berat_badan' => !empty($row[8]) ? (float) str_replace(',', '.', trim($row[8])) : null,
                'tinggi_badan' => !empty($row[9]) ? (float) str_replace(',', '.', trim($row[9])) : null,
                'keterangan' => trim($row[10] ?? '')
            ];

            if ($existing) {
                $existing->update($data);
                $existing->periode = $request->tahun . '-' . $request->bulan . '-01';
                $existing->save(['timestamps' => false]);
                $updateCount++;
            } else {
                $newRecord = new BnbaStunting($data);
                $newRecord->periode = $request->tahun . '-' . $request->bulan . '-01';
                $newRecord->save(['timestamps' => false]);
                $successCount++;
            }
        }
        
        fclose($handle);

        return redirect()->route('stunting.bnba.index')
            ->with('success', "File berhasil diimport! Menambahkan $successCount data baru dan memperbarui $updateCount data lama.");
    }

    public function downloadTemplate()
    {
        // Return a sample Excel template
        $headers = [
            'Nama',
            'Tanggal Lahir (YYYY-MM-DD)',
            'Jenis Kelamin (L/P)',
            'Alamat',
            'RT/RW',
            'Nama Ibu',
            'Nama Ayah',
            'Posyandu',
            'Berat Badan (kg)',
            'Tinggi Badan (cm)',
            'Keterangan',
        ];

        // Create CSV content
        $content = implode(',', $headers) . "\n";
        $content .= "Nama Contoh,2022-01-15,L,Jl. Contoh No. 1,01/02,Ibu Contoh,Ayah Contoh,Posyandu Melati,8.5,70,Keterangan\n";

        return response($content)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="template_bnba_stunting.csv"');
    }
}
