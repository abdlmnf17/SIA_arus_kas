<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;
    protected $table = 'obat';

    protected $fillable = ['nm_obat', 'satuan', 'harga'];

    public function detailMasuk()
    {
        return $this->hasMany(DetailMasuk::class);
    }
    public function detailKeluar()
    {
        return $this->hasMany(DetailKeluar::class);
    }
    public function detailperiksa()
    {
        return $this->hasMany(DetailPeriksa::class);
    }
    public function pasien()
    {
        return $this->hasMany(Pasien::class);
    }

    // public function pasien()
    // {
    //     return $this->belongsToMany(Pasien::class, 'obat_id', 'pasien_id')
    //                 ->withPivot('total') // Jika ada kolom tambahan di tabel pivot
    //                 ->withTimestamps();
    // }
}
