@extends('layouts.app')

@section('content')
    <div class="col-lg-11 mb-10 mx-auto">
        <!-- Menampilkan pesan kesuksesan -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="success">&times;</button>
              {{ session('success') }}.
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <strong>Gagal ditambahkan!</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <br />
        <!-- Project Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <a href="{{ route('jurnal.create') }}" class="btn btn-primary float-right">
                    <i class="fas fa-plus-circle"></i> Tambah
                </a>
                <h4 class="m-15 font-weight-bold">{{ __('Daftar Entri Jurnal') }}</h4>
            </div>
            <div class="card-body">
                <table id="dataTable" class="table table-bordered" cellspacing="1"><br />
                    <thead>
                        <tr align="center">
                            <th style="width: 15%">Tanggal</th>
                            <th style="width: 30%">Keterangan</th>
                            <th style="width: 15%">Akun</th>
                            <th style="width: 20%">Debit</th>
                            <th style="width: 25%">Kredit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jurnals as $jurnal)
                        <tr align="center">
                            <td>
                                @if ($jurnal->detailTransaksi->transaksiKeluar)
                                    {{ $jurnal->detailTransaksi->transaksiKeluar->tgl }}
                                @elseif ($jurnal->detailTransaksi->transaksiMasuk)
                                    {{ $jurnal->detailTransaksi->transaksiMasuk->tgl }}
                                @endif
                            </td>
                            <td>{{ $jurnal->keterangan }}</td>
                            <td>{{ $jurnal->debit }} <br/> {{ $jurnal->kredit }}</td>
                            <td>Rp. {{ number_format($jurnal->total, 2, ',', '.') }}<br/>-</td>
                            <td>-<br/> Rp. {{ number_format($jurnal->total, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach

                        <tr align="center">
                            <td colspan="3"><strong>Total</strong></td>
                            <td><strong>Rp. {{ number_format($totalDebit, 2, ',', '.') }}</strong></td>
                            <td><strong>Rp. {{ number_format($totalKredit, 2, ',', '.') }}</strong></td>
                        </tr>
                    </tbody>

                </table>

            </div>
        </div>
    </div>
@endsection
