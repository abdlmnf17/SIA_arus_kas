<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailPeriksa;
use App\Models\Obat; // Pastikan untuk menggunakan model Obat

class DetailPeriksaController extends Controller
{
    public function destroy($id)
    {
        // Temukan detail pemeriksaan berdasarkan ID
        $detailPeriksa = DetailPeriksa::findOrFail($id);

        // Temukan obat terkait dengan detail pemeriksaan
        $obat = Obat::findOrFail($detailPeriksa->obat_id);

        // Tambahkan jumlah yang dihapus kembali ke stok obat
        $obat->satuan += $detailPeriksa->jumlah;

        // Simpan perubahan stok obat
        $obat->save();

        // Hapus detail pemeriksaan
        $detailPeriksa->delete();

        return redirect()->back()->with('success', 'Detail obat pemeriksaan berhasil dihapus.');
    }
}
