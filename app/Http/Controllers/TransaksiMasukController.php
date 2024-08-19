<?php

namespace App\Http\Controllers;

use App\Models\DetailPeriksa;
use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\Obat;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\TransaksiMasuk;
use App\Models\DetailMasuk;
use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\Validator;

class TransaksiMasukController extends Controller
{
    // Menampilkan form tambah transaksi masuk
    public function create()
    {
        $no_trans = $this->generateNoTransaksi(); // Fungsi untuk menghasilkan nomor transaksi
        return view('transaksi_masuk.create', compact('no_trans'));
    }

    // Menyimpan transaksi masuk
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_trans' => 'required|string|max:255',
            'tgl' => 'required|date',
            'pasien_id' => 'required|exists:pasien,id',
            'keterangan' => 'nullable|string|max:255',
            'harga_periksa' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $transaksi = new TransaksiMasuk();
        $transaksi->no_trans = $request->no_trans;
        $transaksi->tgl = $request->tgl;
        $transaksi->pasien_id = $request->pasien_id;
        $transaksi->keterangan = $request->keterangan;
        $transaksi->harga_periksa = $request->harga_periksa;
        $transaksi->total = $request->total;
        $transaksi->save();

        DetailMasuk::create([
            'transaksi_masuk_id' => $transaksi->id,

        ]);

        DetailTransaksi::create([
            'transaksi_masuk_id' => $transaksi->id,
            'total' => $request->total,
        ]);

        // Tidak menyimpan detail obat di sini, karena akan diatur otomatis melalui JS

        return redirect()->route('transaksi_masuk.index')->with('success', 'Transaksi berhasil disimpan!');
    }

    // Mencari pasien berdasarkan query
    public function searchPasien(Request $request)
    {
        $query = $request->input('query');
        $pasien = Pasien::where('nm_pasien', 'like', "%{$query}%")->get();
        return response()->json($pasien);
    }


    public function getPasienDetails($pasien_id)
    {
        // Menemukan pasien berdasarkan ID
        $pasien = Pasien::findOrFail($pasien_id);

        // Mengambil detail periksa berdasarkan pasien_id
        $detailperiksa = DetailPeriksa::where('pasien_id', $pasien_id)->get();

        // Mengambil data dari detail periksa
        $obat = $detailperiksa->map(function ($detail) {
            return [
                'obat_id' => $detail->obat_id, // Ganti sesuai dengan kolom yang ada di DetailPeriksa
                'jumlah' => $detail->jumlah,
                'harga' => $detail->obat->harga // Pastikan DetailPeriksa memiliki kolom harga
            ];
        });

        // Mengembalikan response JSON
        return response()->json([
            'keterangan' => $pasien->keterangan_dosis, // Pastikan ini adalah kolom yang ada di Pasien
            'obat' => $obat,
        ]);
    }

    // Mengambil detail obat untuk pasien
    public function getDetailObat($pasien_id)
    {
        // Ambil detail periksa berdasarkan pasien_id
        $detailPeriksa = DetailPeriksa::where('pasien_id', $pasien_id)->with('obat')->get();

        // Mapping data obat
        $obatDetails = $detailPeriksa->map(function ($detail) {
            return [
                'obat_id' => $detail->obat->id,
                'nm_obat' => $detail->obat->nm_obat, // Pastikan ini nama kolom yang benar
                'jumlah' => $detail->jumlah,
                'harga' => $detail->obat->harga, // Pastikan ada kolom harga di model Obat
                'subtotal' => $detail->jumlah * $detail->obat->harga
            ];
        });

        return response()->json($obatDetails);
    }

    // Menghasilkan nomor transaksi
    private function generateNoTransaksi()
    {
        $latest = TransaksiMasuk::latest()->first();
        $latest_no = $latest ? intval(substr($latest->no_trans, -4)) : 0;
        $next_no = str_pad($latest_no + 1, 4, '0', STR_PAD_LEFT);
        return 'TRX-' . $next_no;
    }
    public function index()
    {

        $transaksi_masuk = TransaksiMasuk::get();
        return view('transaksi_masuk.index', compact('transaksi_masuk'));
    }

    public function show($id)
    {
        // Ambil transaksi dan pasien yang terkait
        $transaksi = TransaksiMasuk::with(['pasien.detailPeriksa.obat'])->findOrFail($id);

        // Hitung total harga berdasarkan jumlah dan harga
        $detailPeriksa = $transaksi->pasien->detailPeriksa->map(function($detail) {
            $detail->total_harga = $detail->obat->harga * $detail->jumlah;
            return $detail;
        });

        // Hitung total keseluruhan
        $totalKeseluruhan = $detailPeriksa->sum('total_harga');

        return view('transaksi_masuk.detail', compact('transaksi', 'detailPeriksa', 'totalKeseluruhan'));
    }


    public function print($id)
    {

        $transaksi = TransaksiMasuk::with(['pasien.detailPeriksa.obat'])->findOrFail($id);
        // Hitung total harga berdasarkan jumlah dan harga
        $detailPeriksa = $transaksi->pasien->detailPeriksa->map(function($detail) {
            $detail->total_harga = $detail->obat->harga * $detail->jumlah;
            return $detail;
        });

        // Hitung total keseluruhan
        $totalKeseluruhan = $detailPeriksa->sum('total_harga');

        $pdf = PDF::loadView('transaksi_masuk.print', compact('transaksi', 'detailPeriksa'));
        return $pdf->stream('invoice-transaksi.pdf');
    }

    public function destroy($id)
    {
        $transaksi_masuk = TransaksiMasuk::findOrFail($id);


        $transaksi_masuk->detailTransaksi()->each(function ($detailTransaksi) {
            $detailTransaksi->jurnal()->delete();
        });

        $transaksi_masuk->detailTransaksi()->delete();


        $transaksi_masuk->delete();

        return redirect()->route('transaksi_masuk.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
