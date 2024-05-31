@extends('layouts.app')

@section('content')
<div class="col-lg-10 mb-10 mx-auto">
    <!-- Project Card Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="{{ route('obat.index') }}" class="btn btn-primary float-right">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h4 class="m-15 font-weight-bold">TAMBAH OBAT</h4>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('obat.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nm_obat">Nama Obat:</label>
                    <input type="text" class="form-control" id="nm_obat" name="nm_obat" required>
                </div>
                <div class="form-group">
                    <label for="satuan">Satuan:</label>
                    <input type="number" class="form-control" id="satuan" name="satuan" required>
                </div>
                <div class="form-group">
                    <label for="harga">Harga: Rp.</label>
                    <input type="number" class="form-control" id="harga" name="harga" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection
