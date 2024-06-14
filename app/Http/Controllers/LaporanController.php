<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiMasuk;
use App\Models\TransaksiKeluar;
use App\Models\Akun;
use PDF;

class LaporanController extends Controller
{
    public function index()

    {
        $akunKas = Akun::where('nm_akun', 'Kas')->sum('total');
        $akunPen = Akun::where('nm_akun', 'Pendapatan')->sum('total');
        $akunPeng = Akun::where('nm_akun', 'Persediaan')->sum('total');
        return view('laporan.index', compact('akunKas', 'akunPen', 'akunPeng'));
    }


    public function generatePdf(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $tanggal_mulai = $request->input('tanggal_mulai');
        $tanggal_selesai = $request->input('tanggal_selesai');
        $kasmasuk = TransaksiMasuk::whereBetween('tgl', [$tanggal_mulai, $tanggal_selesai])->get();
        $totalKeseluruhan = $kasmasuk->sum('total');

        $pdf = PDF::loadView('laporan.kasmasuk', compact('kasmasuk', 'tanggal_mulai', 'tanggal_selesai', 'totalKeseluruhan'));


        $pdf->setPaper('A4', 'landscape');

        // Unduh PDF
        return $pdf->stream('laporan_'.$tanggal_mulai.'_to_'.$tanggal_selesai.'.pdf');
    }
    public function kaskeluarPDF(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $tanggal_mulai = $request->input('tanggal_mulai');
        $tanggal_selesai = $request->input('tanggal_selesai');
        $kaskeluar = TransaksiKeluar::whereBetween('tgl', [$tanggal_mulai, $tanggal_selesai])->get();
        $totalKeseluruhan = $kaskeluar->sum('total');

        $pdf = PDF::loadView('laporan.kaskeluar', compact('kaskeluar', 'tanggal_mulai', 'tanggal_selesai', 'totalKeseluruhan'));


        $pdf->setPaper('A4', 'landscape');

        // Unduh PDF
        return $pdf->stream('laporan_'.$tanggal_mulai.'_to_'.$tanggal_selesai.'.pdf');
    }
}
