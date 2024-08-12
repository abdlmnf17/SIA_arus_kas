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

        $tanggal = now();
        $no_trans = "KASMASUK/" . $tanggal->format('m-Y') . "/";

        $last_trans = TransaksiMasuk::where('no_trans', 'like', $no_trans . '%')->orderBy('created_at', 'desc')->first();


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
            'min' => ':attribute harus minimal :min.',
            'numeric' => ':attribute harus berupa angka.',
            'date' => ':attribute harus berupa tanggal yang valid.',
            'exists' => ':attribute tidak ditemukan.',
        ];

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


        $errors = [];

        foreach ($request->obat_ids as $index => $obat_id) {
            $jumlah = $request->jumlah[$index];
            $obat = Obat::find($obat_id);

            if (!$obat || $obat->satuan < $jumlah) {
                $namaObat = $obat ? $obat->nm_obat : 'Unknown';
                $errors[] = 'Stok tidak mencukupi untuk obat: ' . $namaObat;
            }
        }

        if ($errors) {
            return redirect()->back()->withErrors($errors)->withInput();
        }

        $transaksi = TransaksiMasuk::create([
            'no_trans' => $request->no_trans,
            'keterangan' => $request->keterangan,
            'pasien_id' => $request->pasien_id,
            'tgl' => $request->tgl,
            'total' => $request->total,
        ]);

        foreach ($request->obat_ids as $index => $obat_id) {
            $jumlah = $request->jumlah[$index];
            $obat = Obat::findOrFail($obat_id);

            DetailMasuk::create([
                'transaksi_masuk_id' => $transaksi->id,
                'obat_id' => $obat_id,
                'jumlah' => $jumlah,
            ]);


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


        $transaksi_masuk->detailTransaksi()->each(function ($detailTransaksi) {
            $detailTransaksi->jurnal()->delete();
        });

        $transaksi_masuk->detailTransaksi()->delete();


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


