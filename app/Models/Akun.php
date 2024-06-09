<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Akun extends Model
{
    use HasFactory;
    protected $table = 'akun';

    protected $fillable = ['nm_akun', 'jenis', 'total'];

   
    public function jurnal()
    {
        return $this->hasMany(Jurnal::class);
    }


}
