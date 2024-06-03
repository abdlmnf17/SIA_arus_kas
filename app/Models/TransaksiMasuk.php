<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiMasuk extends Model
{
    use HasFactory;
    protected $table = 'transaksi_masuk';

    protected $fillable = [
        'no_trans',
        'keterangan',
        'pasien_id',
        'tgl',
        'total',
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

    public function detailMasuk()
    {
        return $this->hasMany(DetailMasuk::class, 'transaksi_masuk_id');
    }
}
