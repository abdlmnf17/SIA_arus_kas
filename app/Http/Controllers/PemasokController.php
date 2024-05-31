<?php

namespace App\Http\Controllers;

use App\Models\Pemasok;
use Illuminate\Http\Request;

class PemasokController extends Controller
{
    public function index()
    {
        $pemasok = Pemasok::all();
        return view('pemasok.pemasok', compact('pemasok'));
    }

    public function create()
    {
        return view('pemasok.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nm_pemasok' => 'required',
            'alamat' => 'required',
            'keterangan' => 'nullable',
        ]);

        Pemasok::create($request->all());

        return redirect()->route('pemasok.index')
                        ->with('success', 'Pemasok berhasil disimpan.');
    }

    public function show(Pemasok $pemasok)
    {
        return view('pemasok.show', compact('pemasok'));
    }

    public function edit(Pemasok $pemasok)
    {
        return view('pemasok.edit', compact('pemasok'));
    }

    public function update(Request $request, Pemasok $pemasok)
    {
        $request->validate([
            'nm_pemasok' => 'required',
            'alamat' => 'required',
            'keterangan' => 'nullable',
        ]);

        $pemasok->update($request->all());

        return redirect()->route('pemasok.index')
                        ->with('success', 'Pemasok berhasil diupdate.');
    }

    public function destroy(Pemasok $pemasok)
    {
        $pemasok->delete();

        return redirect()->route('pemasok.index')
                        ->with('success', 'Pemasok dihapus.');
    }
}
