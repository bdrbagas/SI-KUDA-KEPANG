<?php

namespace App\Http\Controllers\Lingkungan;

use App\Http\Controllers\Controller;
use App\Models\DokumentasiLingkungan;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class LingkunganController extends Controller
{
    public function index(Request $request)
    {
        $query = DokumentasiLingkungan::with(['desa', 'user']);

        // Filter by desa
        if ($request->filled('desa_id')) {
            $query->where('desa_id', $request->desa_id);
        }

        // Filter by jenis kegiatan
        if ($request->filled('jenis_kegiatan')) {
            $query->where('jenis_kegiatan', $request->jenis_kegiatan);
        }

        // Filter by date range
        if ($request->filled('tanggal_dari')) {
            $query->where('tanggal_kegiatan', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->where('tanggal_kegiatan', '<=', $request->tanggal_sampai);
        }

        $dokumentasis = $query->orderBy('tanggal_kegiatan', 'desc')->paginate(12);
        $desas = Desa::all();

        // Stats per jenis kegiatan
        $stats = DokumentasiLingkungan::selectRaw('jenis_kegiatan, COUNT(*) as total')
            ->groupBy('jenis_kegiatan')
            ->get()
            ->keyBy('jenis_kegiatan');

        return view('lingkungan.index', compact('dokumentasis', 'desas', 'stats'));
    }

    public function create()
    {
        $desas = Desa::all();
        return view('lingkungan.create', compact('desas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'jenis_kegiatan' => 'required|in:penanaman_pohon,pembersihan_sampah,penebangan_liar',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_kegiatan' => 'required|date',
            'lokasi' => 'required|string|max:255',
            'jumlah_peserta' => 'required|integer|min:0',
            'penanggung_jawab' => 'nullable|string|max:255',
            'hasil_kegiatan' => 'nullable|string',
            'foto.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // Max 5MB per image
        ]);

        // Handle photo uploads
        $fotoArray = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $foto) {
                $path = $foto->store('dokumentasi-lingkungan', 'public');
                $fotoArray[] = $path;
            }
        }

        $validated['foto'] = $fotoArray;
        $validated['user_id'] = Auth::id() ?? 1; // Default to 1 if not logged in

        DokumentasiLingkungan::create($validated);

        return redirect()->route('lingkungan.index')
            ->with('success', 'Dokumentasi kegiatan berhasil ditambahkan!');
    }

    public function show(DokumentasiLingkungan $lingkungan)
    {
        $lingkungan->load(['desa', 'user']);
        return view('lingkungan.show', compact('lingkungan'));
    }

    public function edit(DokumentasiLingkungan $lingkungan)
    {
        $desas = Desa::all();
        return view('lingkungan.edit', compact('lingkungan', 'desas'));
    }

    public function update(Request $request, DokumentasiLingkungan $lingkungan)
    {
        $validated = $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'jenis_kegiatan' => 'required|in:penanaman_pohon,pembersihan_sampah,penebangan_liar',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_kegiatan' => 'required|date',
            'lokasi' => 'required|string|max:255',
            'jumlah_peserta' => 'required|integer|min:0',
            'penanggung_jawab' => 'nullable|string|max:255',
            'hasil_kegiatan' => 'nullable|string',
            'foto.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        // Handle new photo uploads
        $fotoArray = $lingkungan->foto ?? [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $foto) {
                $path = $foto->store('dokumentasi-lingkungan', 'public');
                $fotoArray[] = $path;
            }
        }

        $validated['foto'] = $fotoArray;

        $lingkungan->update($validated);

        return redirect()->route('lingkungan.index')
            ->with('success', 'Dokumentasi kegiatan berhasil diperbarui!');
    }

    public function destroy(DokumentasiLingkungan $lingkungan)
    {
        // Delete photos from storage
        if ($lingkungan->foto) {
            foreach ($lingkungan->foto as $foto) {
                Storage::disk('public')->delete($foto);
            }
        }

        $lingkungan->delete();

        return redirect()->route('lingkungan.index')
            ->with('success', 'Dokumentasi kegiatan berhasil dihapus!');
    }

    public function deleteFoto(DokumentasiLingkungan $lingkungan, $index)
    {
        $fotoArray = $lingkungan->foto ?? [];

        if (isset($fotoArray[$index])) {
            Storage::disk('public')->delete($fotoArray[$index]);
            unset($fotoArray[$index]);
            $lingkungan->update(['foto' => array_values($fotoArray)]);
        }

        return redirect()->back()->with('success', 'Foto berhasil dihapus!');
    }
}
