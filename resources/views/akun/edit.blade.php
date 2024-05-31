@extends('layouts.app')

@section('content')
<div class="col-lg-10 mb-10 mx-auto">
    <!-- Project Card Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="{{ route('akun.index') }}" class="btn btn-primary float-right">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h4 class="m-15 font-weight-bold">EDIT AKUN</h4>
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

            <form action="{{ route('akun.update', $akun->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="nm_akun">Nama Akun:</label>
                    <input type="text" class="form-control" id="nm_akun" name="nm_akun" value="{{ $akun->nm_akun }}" required>
                </div>
                <div class="form-group">
                    <label for="jenis">Jenis:</label>
                    <input type="text" class="form-control" id="jenis" name="jenis" value="{{ $akun->jenis }}" required>
                </div>
                <div class="form-group">
                    <label for="total">Total:</label>
                    <input type="number" class="form-control" id="total" name="total" value="{{ $akun->total }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection
