<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiKeluar;
use App\Models\DetailKeluar;
use App\Models\Obat;
use App\Models\Barang;
use App\Models\Pemasok;
use App\Models\DetailTransaksi;
use App\Models\Akun;
use PDF;
use Illuminate\Contracts\View\View;

class TransaksiKeluarController extends Controller
{
    public function index()
    {
        $transaksi_keluar = TransaksiKeluar::all();
        return view('transaksi_keluar.index', compact('transaksi_keluar'));
    }

    public function create()
    {
        $obat = Obat::all();
        $barang = Barang::all();
        $pemasok = Pemasok::all();
        return view('transaksi_keluar.create', compact('obat', 'pemasok', 'barang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_trans' => 'required|string|unique:transaksi_keluar,no_trans|max:255',
            'tgl' => 'required|date',
            'pemasok_id' => 'required|integer|exists:pemasok,id',
            'obat_ids.*' => 'required|integer|exists:obat,id',
            'barang_ids.*' => 'required|integer|exists:barang,id',
            'jumlah.*' => 'required|integer|min:1',
            'keterangan' => 'required|string|max:255',
            'total' => 'required|numeric|min:0',
        ], [
            'no_trans.required' => 'No Transaksi wajib diisi.',
            'no_trans.unique' => 'No Transaksi sudah terdaftar. Silakan input yang lain.',
            'no_trans.max' => 'Maksimal 255 karakter untuk No Transaksi.',
            'tgl.required' => 'Tanggal Transaksi wajib diisi.',
            'tgl.date' => 'Tanggal Transaksi harus berupa tanggal yang valid.',
            'pemasok_id.required' => 'Nama Pemasok wajib diisi.',
            'pemasok_id.integer' => 'Nama Pemasok harus berupa angka.',
            'pemasok_id.exists' => 'Nama Pemasok tidak valid.',
            'obat_ids.*.required' => 'Nama Obat wajib diisi.',
            'obat_ids.*.integer' => 'Nama Obat harus berupa angka.',
            'obat_ids.*.exists' => 'Nama Obat tidak valid.',
            'barang_ids.*.required' => 'Nama Barang wajib diisi.',
            'barang_ids.*.integer' => 'Nama Barang harus berupa angka.',
            'barang_ids.*.exists' => 'Nama Barang tidak valid.',
            'jumlah.*.required' => 'Jumlah wajib diisi.',
            'jumlah.*.integer' => 'Jumlah harus berupa angka.',
            'jumlah.*.min' => 'Jumlah minimal 1.',
            'keterangan.required' => 'Keterangan wajib diisi.',
            'keterangan.string' => 'Keterangan harus berupa teks.',
            'keterangan.max' => 'Maksimal 255 karakter untuk Keterangan.',
            'total.required' => 'Total wajib diisi.',
            'total.numeric' => 'Total harus berupa angka.',
            'total.min' => 'Total minimal 0.',
        ]);



        // Membuat detail keluar
        if (count($request->obat_ids) !== count($request->barang_ids)) {
            // Handle error
            return redirect()->back()->withInput()->withErrors('Jumlah elemen dalam obat dan barang harus sama, silahkan pilih tambah "Tidak Ada" jika salah satu jumlahnya berbeda');
        } else {

            $transaksi = TransaksiKeluar::create([
                'no_trans' => $request->no_trans,
                'keterangan' => $request->keterangan,
                'pemasok_id' => $request->pemasok_id,
                'tgl' => $request->tgl,
                'total' => $request->total,
            ]);

            for ($i = 0; $i < count($request->obat_ids); $i++) {
                $obat_id = $request->obat_ids[$i];
                $barang_id = $request->barang_ids[$i];
                $jumlah = $request->jumlah[$i];

                DetailKeluar::create([
                    'transaksi_keluar_id' => $transaksi->id,
                    'obat_id' => $obat_id,
                    'barang_id' => $barang_id,
                ]);

                // Menambah stok obat
                $obat = Obat::findOrFail($obat_id);
                $obat->satuan += $jumlah;
                $obat->save();

                // Menambah stok barang
                $barang = Barang::findOrFail($barang_id);
                $barang->satuan += $jumlah;
                $barang->save();
            }
        }

        DetailTransaksi::create([
            'transaksi_keluar_id' => $transaksi->id,
            'total' => $request->total,
        ]);





        return redirect()->route('transaksi_keluar.index')->with('success', 'Transaksi berhasil disimpan');
    }

    public function show($id)
    {

        $transaksi = TransaksiKeluar::with(['detailKeluar', 'pemasok', 'detailKeluar.barang'])->findOrFail($id);
        return view('transaksi_keluar.detail', compact('transaksi'));
    }

    public function destroy($id)
    {
        $transaksi_keluar = TransaksiKeluar::findOrFail($id);

        // Hapus semua entri terkait dalam tabel 'jurnal' yang merujuk ke entri dalam tabel 'detail_transaksi'
        $transaksi_keluar->detailTransaksi()->each(function ($detailTransaksi) {
            $detailTransaksi->jurnal()->delete();
        });
        $transaksi_keluar->detailTransaksi()->delete();
        $transaksi_keluar->delete();

        return redirect()->route('transaksi_keluar.index')->with('success', 'Transaksi berhasil dihapus.');
    }


    public function searchPemasok(Request $request)
    {
        $search = $request->get('query');
        $result = Pemasok::where('nm_pemasok', 'LIKE', '%' . $search . '%')->get();
        return response()->json($result);
    }

    public function print($id)
    {
        $transaksi = TransaksiKeluar::with(['detailKeluar.obat', 'detailKeluar.barang', 'pemasok'])->findOrFail($id);
        $pdf = PDF::loadView('transaksi_keluar.print', compact('transaksi'));
        return $pdf->stream('invoice-transaksi.pdf');
    }
}
