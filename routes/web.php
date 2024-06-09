<?php

use App\Http\Controllers\TransaksiMasukController;
use App\Http\Controllers\TransaksiKeluarController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('user',App\Http\Controllers\UserController::class);
    Route::resource('pasien',App\Http\Controllers\PasienController::class);
    Route::resource('pemasok',App\Http\Controllers\PemasokController::class);
    Route::resource('obat',App\Http\Controllers\ObatController::class);
    Route::resource('barang',App\Http\Controllers\BarangController::class);
    Route::resource('akun',App\Http\Controllers\AkunController::class);
    Route::resource('transaksi_masuk',App\Http\Controllers\TransaksiMasukController::class);
    Route::get('/search-pasien', [TransaksiMasukController::class, 'searchPasien'])->name('search.pasien');
    Route::get('/search-pemasok', [TransaksiKeluarController::class, 'searchPemasok'])->name('search.pemasok');

    Route::resource('jurnal',App\Http\Controllers\JurnalController::class);

    Route::get('/transaksi_masuk/{id}/print', [TransaksiMasukController::class, 'print'])->name('transaksi_masuk.print');
    Route::get('/transaksi_keluar/{id}/print', [TransaksiKeluarController::class, 'print'])->name('transaksi_keluar.print');
});


Route::middleware(['auth'])->group(function () {

    Route::resource('pasien',App\Http\Controllers\PasienController::class);
    Route::resource('pemasok',App\Http\Controllers\PemasokController::class);
    Route::resource('obat',App\Http\Controllers\ObatController::class);
    Route::resource('barang',App\Http\Controllers\BarangController::class);
    Route::resource('transaksi_masuk',App\Http\Controllers\TransaksiMasukController::class);
    Route::resource('transaksi_keluar',App\Http\Controllers\TransaksiKeluarController::class);
    Route::get('/search-pasien', [TransaksiMasukController::class, 'searchPasien'])->name('search.pasien');
    Route::get('/search-pemasok', [TransaksiKeluarController::class, 'searchPemasok'])->name('search.pemasok');
    Route::resource('jurnal',App\Http\Controllers\JurnalController::class);
    Route::get('/transaksi_masuk/{id}/print', [TransaksiMasukController::class, 'print'])->name('transaksi_masuk.print');
    Route::get('/transaksi_keluar/{id}/print', [TransaksiKeluarController::class, 'print'])->name('transaksi_keluar.print');
});

