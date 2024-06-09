@extends('layouts.app')

@section('content')
    <div class="col-lg-9 mb-4 mx-auto">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h4 class="m-15 font-weight-bold">{{ __('Laporan Kas') }}</h4>
            </div>
            <div class="card-body">
                <div class="card-header py-3">
                    <h5 class="m-15 font-weight-bold">{{ __('Kas Masuk') }}</h5>
                </div>
                <br />
                <form action="{{ route('laporan.kas') }}" method="POST">
                    @csrf
                    <div class="form-row align-items-end">
                        <div class="form-group col-md-4">
                            <label for="tanggal_mulai">Tanggal Mulai:</label>
                            <input type="date" name="tanggal_mulai" class="form-control form-control-sm" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="tanggal_selesai">Tanggal Selesai:</label>
                            <input type="date" name="tanggal_selesai" class="form-control form-control-sm" required>
                        </div>
                        <div class="form-group col-md-4">
                            <button type="submit" class="btn btn-success btn-sm">Cetak</button>
                        </div>
                    </div>
                </form>
                <br />
                <br />
                <div class="card-header py-3">
                    <h5 class="m-15 font-weight-bold">{{ __('Kas Keluar') }}</h5>
                </div>
                <br />
                <form action="{{ route('laporan.kaskeluar') }}" method="POST">
                    @csrf
                    <div class="form-row align-items-end">
                        <div class="form-group col-md-4">
                            <label for="tanggal_mulai">Tanggal Mulai:</label>
                            <input type="date" name="tanggal_mulai" class="form-control form-control-sm" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="tanggal_selesai">Tanggal Selesai:</label>
                            <input type="date" name="tanggal_selesai" class="form-control form-control-sm" required>
                        </div>
                        <div class="form-group col-md-4">
                            <button type="submit" class="btn btn-success btn-sm">Cetak</button>
                        </div>
                    </div>
                </form>
                <br />
                <br />


            </div>
        </div>
    </div>
@endsection
