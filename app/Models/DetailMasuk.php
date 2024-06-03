<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailMasuk extends Model
{
    use HasFactory;
    protected $table = 'detail_masuk';

    protected $fillable = [
        'transaksi_masuk_id',
        'obat_id',
    ];

    public function transaksiMasuk()
    {
        return $this->belongsTo(TransaksiMasuk::class, 'transaksi_masuk_id');
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class);
    }
}
