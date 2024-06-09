@extends('layouts.app')

@section('content')
<div class="col-lg-11 mb-9 mx-auto">
    <!-- Project Card Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="{{ route('transaksi_keluar.create') }}" class="btn btn-primary float-right">
                <i class="fas fa-plus"></i>
            </a>
            <h4 class="m-15 font-weight-bold">DAFTAR TRANSAKSI KAS KELUAR</h4>
        </div>
        <div class="card-body">
            <!-- Menampilkan pesan kesuksesan -->
            @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
            @endif

            <table id="dataTable" class="table table-bordered" cellspacing="1"><br/>
                <thead>
                    <tr align="center">
                        <th style="width: 5%">Tanggal</th>
                        <th style="width: 10%">No Transaksi</th>
                        <th style="width: 25%">Keterangan</th>
                        <th style="width: 10%">Pemasok</th>
            
                        <th style="width: 15%">Total</th>
                        <th style="width: 15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi_keluar as $transaksi)
                    <tr align="center">
                        <td>{{ $transaksi->tgl }}</td>
                        <td>{{ $transaksi->no_trans }}</td>
                        <td>{{ $transaksi->keterangan }}</td>
                        <td>{{ $transaksi->pemasok->nm_pemasok }}</td>

                        <td>Rp. {{ number_format($transaksi->total, 2, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('transaksi_keluar.show', $transaksi->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-print"></i> Detail
                            </a>
                            <!-- Tombol Hapus -->
                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#confirmDeleteModal{{ $transaksi->id }}">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </td>
                    </tr>

                    <!-- Modal Konfirmasi Hapus -->
                    <div class="modal fade" id="confirmDeleteModal{{ $transaksi->id }}" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form method="post" action="{{ route('transaksi_keluar.destroy', $transaksi->id) }}">
                                    @csrf
                                    @method('delete')
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Hapus</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Apakah Anda yakin ingin menghapus ini?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
