<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Akun;
use App\Models\Barang;
use App\Models\Obat;
use App\Models\Pasien;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $akunKas = Akun::where('nm_akun', 'Kas')->sum('total');
        $akunPen = Akun::where('nm_akun', 'Pendapatan')->sum('total');
        $akunPeng = Akun::where('nm_akun', 'Persediaan')->sum('total');
        $barang = Barang::count();
        $obat = Obat::count();
        $pasien = Pasien::count();

        return view('home', compact('akunKas', 'akunPen', 'akunPeng', 'barang', 'obat', 'pasien'));
    }
}
