<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Akun;
use App\Models\DetailTransaksi;
use App\Models\Jurnal;


class JurnalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $akuns = Akun::all();
        $jurnals = Jurnal::all();
        $detailTransaksis = DetailTransaksi::all();
        $totalDebit = $jurnals->sum('total');
        $totalKredit = $jurnals->sum('total');

        return view('jurnal.index', compact('akuns', 'jurnals', 'detailTransaksis', 'totalDebit', 'totalKredit'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         // Mendapatkan daftar akun
         $akuns = Akun::all();

         // Mendapatkan daftar transaksi masuk dan keluar
         $detailTransaksis = DetailTransaksi::all();

         return view('jurnal.create', compact('akuns', 'detailTransaksis'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'keterangan' => 'required|string',
            'detail_transaksi_id' => 'required|exists:detail_transaksi,id',
            'debit' => 'required|string',
            'kredit' => 'required|string',
            'total' => 'required|integer',
        ]);

        // Membuat entri jurnal baru
        $jurnal = Jurnal::create([
            'keterangan' => $request->keterangan,
            'detail_transaksi_id' => $request->detail_transaksi_id,
            'debit' => $request->debit,
            'kredit' => $request->kredit,
            'total' => $request->total,
        ]);

        // Jika akun_id debit ditemukan, tambahkan total
        if ($request->debit) {
            $akun = Akun::where('nm_akun', $request->debit)->first();
            if ($akun) {
                $akun->total += $request->total;
                $akun->save();
            }
        }

        if ($request->kredit) {
            $akun = Akun::where('nm_akun', $request->kredit)->first();
            if ($akun) {
                if ($akun->nm_akun === 'Pendapatan') {
                    // Jika nama akun adalah Pendapatan, tambahkan total
                    $akun->total += $request->total;
                } else {
                    // Jika nama akun bukan Pendapatan, kurangi total
                    $akun->total -= $request->total;
                }
                $akun->save();
            }
        }



        return redirect()->route('jurnal.index')->with('success', 'Entri jurnal berhasil disimpan.');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
