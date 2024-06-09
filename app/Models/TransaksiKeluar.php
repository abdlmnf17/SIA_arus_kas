<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiKeluar extends Model
{
    use HasFactory;
    protected $table = 'transaksi_keluar';

    protected $fillable = [
        'no_trans',
        'keterangan',
        'pemasok_id',
        'tgl',
        'total',
    ];



    public function detailKeluar()
    {
        return $this->hasMany(DetailKeluar::class, 'transaksi_keluar_id');
    }
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_keluar_id');
    }

    public function pemasok() {
        return $this->belongsTo(Pemasok::class, 'pemasok_id');
    }

    public function barang() {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

}
