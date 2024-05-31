<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use Illuminate\Http\Request;

class AkunController extends Controller
{
    public function index()
    {
        $akun = Akun::all();
        return view('akun.akun', compact('akun'));
    }

    public function create()
    {
        return view('akun.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nm_akun' => 'required',
            'jenis' => 'required',
            'total' => 'required|integer',
        ]);

        Akun::create($request->all());

        return redirect()->route('akun.index')
                        ->with('success', 'Akun berhasil disimpan.');
    }

    public function show(Akun $akun)
    {
        return view('akun.show', compact('akun'));
    }

    public function edit(Akun $akun)
    {
        return view('akun.edit', compact('akun'));
    }

    public function update(Request $request, Akun $akun)
    {
        $request->validate([
            'nm_akun' => 'required',
            'jenis' => 'required',
            'total' => 'required|integer',
        ]);

        $akun->update($request->all());

        return redirect()->route('akun.index')
                        ->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy(Akun $akun)
    {
        $akun->delete();

        return redirect()->route('akun.index')
                        ->with('success', 'Akun berhasil dihapus.');
    }
}
