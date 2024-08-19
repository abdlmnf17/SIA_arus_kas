<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;
    protected $table = 'pasien';

    protected $fillable = ['nm_pasien', 'umur', 'alamat', 'tensi', 'keluhan', 'diagnosa', 'keterangan_dosis', 'no_antrian', 'total'];

    public function transaksiMasuk()
    {
        return $this->hasMany(TransaksiMasuk::class);
    }

    public function detailPeriksa()
    {
        return $this->hasMany(DetailPeriksa::class);
    }
    public function obat()
    {
        return $this->hasMany(Obat::class);
    }
    // public function obat()
    // {
    //     return $this->belongsToMany(Obat::class, 'pasien_id', 'obat_id')
    //                 ->withPivot('total') // Jika ada kolom tambahan di tabel pivot
    //                 ->withTimestamps();
    // }
}
