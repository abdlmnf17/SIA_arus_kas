<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiMasuk;
use App\Models\DetailMasuk;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\DetailTransaksi;
use App\Models\Jurnal;
use PDF;

class TransaksiMasukController extends Controller
{
    public function index()
    {
        $transaksi_masuk = TransaksiMasuk::all();

        return view('transaksi_masuk.index', compact('transaksi_masuk'));
    }

    public function create()
    {
        $obat = Obat::all();
        $pasien = Pasien::all();
        // Generate nomor transaksi
        $tanggal = now(); // atau Anda dapat mengatur tanggal sesuai kebutuhan
        $no_trans = "KASMASUK/" . $tanggal->format('m-Y') . "/";

        // Cari nomor transaksi terakhir dari database
        $last_trans = TransaksiMasuk::where('no_trans', 'like', $no_trans . '%')->orderBy('created_at', 'desc')->first();

        // Jika nomor transaksi sudah ada di database, tambahkan angka di belakangnya
        if ($last_trans) {
            $last_no = explode('/', $last_trans->no_trans);
            $last_num = intval(end($last_no));
            $no_trans .= str_pad($last_num + 1, 2, '0', STR_PAD_LEFT);
        } else {
            $no_trans .= '01';
        }


        return view('transaksi_masuk.create', compact('obat', 'pasien', 'no_trans'));
    }

    public function store(Request $request)
    {
        $messages = [
            'required' => ':attribute wajib diisi.',
            'unique' => ':attribute sudah terdaftar. Silakan input yang lain.',
            'max' => 'Maksimal :max karakter.',
        ];

        // Validasi request
        $request->validate([
            'tgl' => 'required|date',
            'pasien_id' => 'required|integer|exists:pasien,id',
            'obat_ids.*' => 'integer|exists:obat,id',
            'jumlah.*' => 'required|integer|min:1',
            'keterangan' => 'required|string|max:255',
            'total' => 'required|numeric|min:0',
        ], $messages, [
            'tgl' => 'Tanggal Transaksi',
            'pasien_id' => 'Nama Pasien',
            'obat_ids.*' => 'Nama Obat',
            'jumlah.*' => 'Jumlah',
            'keterangan' => 'Keterangan',
            'total' => 'Total',
        ]);


        // Membuat transaksi masuk
        $transaksi = TransaksiMasuk::create([
            'no_trans' => $request->no_trans,
            'keterangan' => $request->keterangan,
            'pasien_id' => $request->pasien_id,
            'tgl' => $request->tgl,
            'total' => $request->total,
        ]);

        // Membuat detail masuk
        foreach ($request->obat_ids as $index => $obat_id) {
            $jumlah = $request->jumlah[$index];

            DetailMasuk::create([
                'transaksi_masuk_id' => $transaksi->id,
                'obat_id' => $obat_id,
            ]);

            // Mengurangi stok obat
            $obat = Obat::findOrFail($obat_id);
            $obat->satuan -= $jumlah;
            $obat->save();
        }

        DetailTransaksi::create([
            'transaksi_masuk_id' => $transaksi->id,
            'total' => $request->total,
        ]);




        return redirect()->route('transaksi_masuk.index')->with('success', 'Transaksi berhasil disimpan');
    }


    public function show($id)
    {
        $transaksi = TransaksiMasuk::with(['detailMasuk.obat', 'pasien'])->findOrFail($id);
        return view('transaksi_masuk.detail', compact('transaksi'));
    }


    public function destroy($id)
{
    $transaksi_masuk = TransaksiMasuk::findOrFail($id);

    // Hapus semua entri terkait dalam tabel 'jurnal' yang merujuk ke entri dalam tabel 'detail_transaksi'
    $transaksi_masuk->detailTransaksi()->each(function ($detailTransaksi) {
        $detailTransaksi->jurnal()->delete();
    });

    // Hapus semua entri dalam tabel 'detail_transaksi' yang terkait dengan transaksi masuk ini
    $transaksi_masuk->detailTransaksi()->delete();

    // Terakhir, hapus transaksi masuk itu sendiri
    $transaksi_masuk->delete();

    return redirect()->route('transaksi_masuk.index')->with('success', 'Transaksi berhasil dihapus.');
}


    public function searchPasien(Request $request)
    {
        $search = $request->get('query');
        $result = Pasien::where('nm_pasien', 'LIKE', '%' . $search . '%')->get();
        return response()->json($result);
    }

    public function print($id)
    {
        $transaksi = TransaksiMasuk::with(['detailMasuk.obat', 'pasien'])->findOrFail($id);
        $pdf = PDF::loadView('transaksi_masuk.print', compact('transaksi'));
        return $pdf->stream('invoice-transaksi.pdf');
    }
}
