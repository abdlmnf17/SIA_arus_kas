<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::all();
        return view('barang.barang', compact('barang'));
    }

    public function create()
    {
        return view('barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nm_brg' => 'required',
            'satuan' => 'required',
            'harga' => 'required|integer',
        ]);

        Barang::create($request->all());

        return redirect()->route('barang.index')
                        ->with('success', 'Barang berhasil disimpan.');
    }

    public function show(Barang $barang)
    {
        return view('barang.show', compact('barang'));
    }

    public function edit(Barang $barang)
    {
        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nm_brg' => 'required',
            'satuan' => 'required',
            'harga' => 'required|integer',
        ]);

        $barang->update($request->all());

        return redirect()->route('barang.index')
                        ->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();

        return redirect()->route('barang.index')
                        ->with('success', 'Barang berhasil dihapus.');
    }
}
