<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;
    protected $table = 'pasien';

    protected $fillable = ['nm_pasien', 'umur', 'alamat', 'tensi'];

    public function transaksiMasuk()
    {
        return $this->hasMany(TransaksiMasuk::class);
    }
}
