<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Akun;
use Illuminate\Support\Carbon;

class AkunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Tambahkan data akun ke dalam tabel 'akun'
        Akun::create([
            'nm_akun' => 'Kas',
            'jenis' => 'Asset',
            'total' => 1000000,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Akun::create([
            'nm_akun' => 'Persediaan',
            'jenis' => 'Asset',
            'total' => 1000000,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Akun::create([
            'nm_akun' => 'Utang Usaha',
            'jenis' => 'Utang',
            'total' => 1000000,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Akun::create([
            'nm_akun' => 'Pendapatan',
            'jenis' => 'Pendapatan Usaha',
            'total' => 1000000,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Akun::create([
            'nm_akun' => 'Beban Lain',
            'jenis' => 'Beban',
            'total' => 1000000,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Tambahkan data lain jika diperlukan
    }
}
