<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    use HasFactory;
    protected $table = 'jurnal';

    protected $fillable = [
        'keterangan',
        'detail_transaksi_id',
       
        'debit',
        'kredit',
        'total',

    ];

    public function akun()
    {
        return $this->belongsTo(Akun::class);
    }

    public function detailTransaksi()
    {
        return $this->belongsTo(DetailTransaksi::class);
    }

}
