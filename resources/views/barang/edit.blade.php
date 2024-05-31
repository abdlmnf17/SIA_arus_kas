@extends('layouts.app')

@section('content')
<div class="col-lg-10 mb-10 mx-auto">
    <!-- Project Card Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="{{ route('barang.index') }}" class="btn btn-primary float-right
            ">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h4 class="m-15 font-weight-bold">EDIT BARANG</h4>
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

        <form action="{{ route('barang.update', $barang->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="nm_brg">Nama Barang:</label>
                <input type="text" class="form-control" id="nm_brg" name="nm_brg" value="{{ $barang->nm_brg }}" required>
            </div>
            <div class="form-group">
                <label for="satuan">Satuan:</label>
                <input type="number" class="form-control" id="satuan" name="satuan" value="{{ $barang->satuan }}" required>
            </div>
            <div class="form-group">
                <label for="harga">Harga:</label>
                <input type="number" class="form-control" id="harga" name="harga" value="{{ $barang->harga }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
</div>
@endsection