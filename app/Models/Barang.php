<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    protected $table = 'barang';

    protected $fillable = ['nm_brg', 'satuan', 'harga'];

    public function detailKeluar()
    {
        return $this->hasMany(DetailKeluar::class);
    }
}
