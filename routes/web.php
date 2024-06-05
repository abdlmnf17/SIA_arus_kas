<?php

use App\Http\Controllers\TransaksiMasukController;
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
    Route::get('/transaksi_masuk/{id}/print', [TransaksiMasukController::class, 'print'])->name('transaksi_masuk.print');
});


Route::middleware(['auth'])->group(function () {

    Route::resource('pasien',App\Http\Controllers\PasienController::class);
    Route::resource('pemasok',App\Http\Controllers\PemasokController::class);
    Route::resource('obat',App\Http\Controllers\ObatController::class);
    Route::resource('barang',App\Http\Controllers\BarangController::class);
    Route::resource('transaksi_masuk',App\Http\Controllers\TransaksiMasukController::class);
    Route::get('/search-pasien', [TransaksiMasukController::class, 'searchPasien'])->name('search.pasien');
    Route::get('/transaksi_masuk/{id}/print', [TransaksiMasukController::class, 'print'])->name('transaksi_masuk.print');
});

