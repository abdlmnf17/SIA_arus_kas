<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\Obat;
use App\Models\DetailPeriksa;
use Illuminate\Http\Request;

class PasienController extends Controller
{
    public function index()
    {
        $pasien = Pasien::all();
        return view('pasien.pasien', compact('pasien'));
    }

    public function lihatPemeriksa()
    {
        $pasien = Pasien::all();
        return view('pasien.pasien', compact('pasien'));
    }

    public function create()
    {
        $tanggal = now();
        $no_antrian = $tanggal->format('m-Y') . "/" . "ANTRIAN" . "-";

        // Mengambil nomor antrian terakhir yang sesuai dengan format
        $no_terakhir = Pasien::where('no_antrian', 'like', $no_antrian . '%')
            ->orderBy('no_antrian', 'desc')
            ->first();

        if ($no_terakhir) {
            // Memecah nomor antrian terakhir untuk mengambil angka di akhir
            $last_no = explode('-', $no_terakhir->no_antrian);
            $last_num = intval(end($last_no));

            // Menambahkan 1 pada nomor terakhir dan mengisi dengan angka nol di depan jika perlu
            $no_antrian .= str_pad($last_num + 1, 2, '0', STR_PAD_LEFT);
        } else {
            // Jika tidak ada nomor antrian sebelumnya, mulai dengan 01
            $no_antrian .= '01';
        }

        return view('pasien.create', compact('no_antrian'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nm_pasien' => 'required|regex:/^[a-zA-Z\s]+$/',
            'umur' => 'required',
            'alamat' => 'required',
            'tensi' => 'required',
            'no_antrian' => 'required',
            'keluhan' => 'required',
            'diagnosa' => 'nullable',
            'keterangan_dosis' => 'nullable',
            'total' => 'nullable',
        ]);

        Pasien::create($request->all());

        return redirect()->route('pasien.index')
            ->with('success', 'Pasien berhasil ditambahkan.');
    }

    public function show(Pasien $pasien)
    {
        return view('pasien.show', compact('pasien'));
    }

    public function edit(Pasien $pasien)
    {
        $obat = Obat::all();
        return view('pasien.update', compact('pasien', 'obat'));
    }
    public function editPemeriksa(Pasien $pasien)
    {
        // Ambil semua detail pemeriksaan berdasarkan ID pasien
        $detailPeriksa = $pasien->detailPeriksa; // Menggunakan relasi
        $obat = Obat::all(); // Ambil semua obat untuk ditampilkan di form

        return view('pasien.update', compact('pasien', 'obat', 'detailPeriksa'));
    }


    public function update(Request $request, Pasien $pasien)
    {
        $request->validate([
            'nm_pasien' => 'required|regex:/^[a-zA-Z\s]+$/',
            'umur' => 'required',
            'alamat' => 'required',
            'tensi' => 'required',
            'keluhan' => 'required',
            'diagnosa' => 'nullable',
            'keterangan_dosis' => 'nullable',
            'total' => 'nullable',
        ]);

        $pasien->update($request->all());

        return redirect()->route('pasien.index')
            ->with('success', 'Pasien berhasil update.');
    }

    public function destroy(Pasien $pasien)
    {
        $pasien->delete();

        return redirect()->route('pasien.index')
            ->with('success', 'Pasien berhasil hapus.');
    }

    public function updatePemeriksa(Request $request, Pasien $pasien)
    {

        // Validasi input dari request
        $request->validate([
            'nm_pasien' => 'required|regex:/^[a-zA-Z\s]+$/',
            'umur' => 'required',
            'alamat' => 'required',
            'tensi' => 'required',
            'keluhan' => 'required',
            'diagnosa' => 'nullable',
            'keterangan_dosis' => 'nullable',
            'total' => 'nullable',
            'obat_ids.*' => 'integer|exists:obat,id',
            'jumlah.*' => 'required|integer|min:1',
        ]);

        $errors = [];

        // Cek ketersediaan stok obat
        foreach ($request->obat_ids as $index => $obat_id) {
            $jumlah = $request->jumlah[$index];
            $obat = Obat::find($obat_id);

            if (!$obat || $obat->satuan < $jumlah) {
                $namaObat = $obat ? $obat->nm_obat : 'Unknown';
                $errors[] = 'Stok tidak mencukupi untuk obat: ' . $namaObat;
            }
        }

        if ($errors) {
            return redirect()->back()->withErrors($errors)->withInput();
        }

        // Perbarui data pasien
        $pasien->update([
            'nm_pasien' => $request->nm_pasien,
            'umur' => $request->umur,
            'alamat' => $request->alamat,
            'tensi' => $request->tensi,
            'keluhan' => $request->keluhan,
            'diagnosa' => $request->diagnosa,
            'total' => $request->total,
            'keterangan_dosis' => $request->keterangan_dosis,
        ]);

        // Simpan detail periksa dan kurangi stok obat
        foreach ($request->obat_ids as $index => $obat_id) {
            $jumlah = $request->jumlah[$index];
            $obat = Obat::findOrFail($obat_id);

            // Temukan atau buat record DetailPeriksa berdasarkan pasien_id dan obat_id
            $detailPeriksa = DetailPeriksa::updateOrCreate(
                ['pasien_id' => $pasien->id, 'obat_id' => $obat_id],
                ['jumlah' => $jumlah] // Update atribut lain jika diperlukan
            );

            // Kurangi stok obat
            $obat->satuan -= $jumlah;
            $obat->save();
        }


        return redirect()->route('pasien.lihat')
            ->with('success', 'Pasien berhasil diupdate.');
    }

}
