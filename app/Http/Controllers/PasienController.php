<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\Request;

class PasienController extends Controller
{
    public function index()
    {
        $pasien = Pasien::all();
        return view('pasien.pasien', compact('pasien'));
    }

    public function create()
    {
        return view('pasien.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nm_pasien' => 'required',
            'umur' => 'required',
            'alamat' => 'required',
            'tensi' => 'required',
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
        return view('pasien.update', compact('pasien'));
    }

    public function update(Request $request, Pasien $pasien)
    {
        $request->validate([
            'nm_pasien' => 'required',
            'umur' => 'required',
            'alamat' => 'required',
            'tensi' => 'required',
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
}
