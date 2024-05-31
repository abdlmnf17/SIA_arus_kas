@extends('layouts.app')

@section('content')
<div class="col-lg-10 mb-10 mx-auto">
    <!-- Project Card Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="{{ route('pemasok.index') }}" class="btn btn-primary float-right">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h4 class="m-15 font-weight-bold">EDIT PEMASOK</h4>
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

            <form action="{{ route('pemasok.update', $pemasok->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="nm_pemasok">Nama Pemasok:</label>
                    <input type="text" class="form-control" id="nm_pemasok" name="nm_pemasok" value="{{ $pemasok->nm_pemasok }}" required>
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat:</label>
                    <input type="text" class="form-control" id="alamat" name="alamat" value="{{ $pemasok->alamat }}" required>
                </div>
                <div class="form-group">
                    <label for="keterangan">Keterangan:</label>
                    <textarea class="form-control" id="keterangan" name="keterangan">{{ $pemasok->keterangan }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection
