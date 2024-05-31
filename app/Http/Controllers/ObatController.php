<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    public function index()
    {
        $obat = Obat::all();
        return view('obat.obat', compact('obat'));
    }

    public function create()
    {
        return view('obat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nm_obat' => 'required',
            'satuan' => 'required',
            'harga' => 'required|integer',
        ]);

        Obat::create($request->all());

        return redirect()->route('obat.index')
                        ->with('success', 'Obat berhasil disimpan.');
    }

    public function show(Obat $obat)
    {
        return view('obat.show', compact('obat'));
    }

    public function edit(Obat $obat)
    {
        return view('obat.edit', compact('obat'));
    }

    public function update(Request $request, Obat $obat)
    {
        $request->validate([
            'nm_obat' => 'required',
            'satuan' => 'required',
            'harga' => 'required|integer',
        ]);

        $obat->update($request->all());

        return redirect()->route('obat.index')
                        ->with('success', 'Obat berhasil diperbarui.');
    }

    public function destroy(Obat $obat)
    {
        $obat->delete();

        return redirect()->route('obat.index')
                        ->with('success', 'Obat berhasil dihapus.');
    }
}
