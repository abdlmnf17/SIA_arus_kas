<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    use HasFactory;
    protected $table = 'detail_transaksi';

    protected $fillable = [
       'transaksi_masuk_id',
       'transaksi_keluar_id',
       'total',

    ];



    public function transaksiMasuk()
    {
        return $this->belongsTo(TransaksiMasuk::class, 'transaksi_masuk_id');
    }


    public function transaksiKeluar()
    {
        return $this->belongsTo(TransaksiKeluar::class, 'transaksi_keluar_id');
    }

    public function jurnal()
    {
        return $this->hasMany(Jurnal::class);
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class);
    }


}
