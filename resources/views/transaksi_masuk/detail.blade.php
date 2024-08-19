@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Detail Transaksi Masuk</div>

                    <div class="card-body">
                        <div class="mb-3">
                            <label>No Transaksi:</label>
                            <input type="text" class="form-control" value="{{ $transaksi->no_trans }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label>Tanggal:</label>
                            <input type="text" class="form-control" value="{{ $transaksi->tgl }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label>Nama Pasien:</label>
                            <input type="text" class="form-control" value="{{ $transaksi->pasien->nm_pasien }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label>Keterangan:</label>
                            <input type="text" class="form-control" value="{{ $transaksi->keterangan }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label>Keluhan:</label>
                            <input type="text" class="form-control" value="{{ $transaksi->pasien->keluhan }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label>Diagnosa:</label>
                            <input type="text" class="form-control" value="{{ $transaksi->pasien->diagnosa }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label>Detail Obat:</label>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama Obat</th>
                                        <th>Harga Satuan</th>
                                        <th>Jumlah</th>
                                        <th>Total</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($detailPeriksa as $detail)
                                        <tr>
                                            <td>{{ $detail->obat->nm_obat }}</td>
                                            <td>Rp. {{ number_format($detail->obat->harga, 2, ',', '.') }}</td>
                                            <td>{{ $detail->jumlah }}</td>
                                            <td>Rp. {{ number_format($detail->total_harga, 2, ',', '.') }}</td>

                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td>Harga Periksa</td>

                                        <td></td>
                                        <td>
                                        <td>Rp. {{ number_format($transaksi->harga_periksa, 2, ',', '.') }}</td>
                                        </td>
                                    </tr>
                                </tbody>

                            </table>
                        </div>
                        <div class="mb-3">
                            <label>Keterangan Dosis:</label>
                            <input type="text" class="form-control" value="{{ $transaksi->pasien->keterangan_dosis }}"
                                readonly>
                        </div>

                        <div class="mb-3">
                            <label>Total:</label>
                            <input type="text" class="form-control"
                                value="Rp. {{ number_format($transaksi->total, '2', ',', '.') }}" readonly>
                        </div>

                        <a href="{{ route('transaksi_masuk.index') }}" class="btn btn-primary">Kembali</a>
                        <a href="{{ route('transaksi_masuk.print', $transaksi->id) }}" class="btn btn-success">Cetak
                            PDF</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
