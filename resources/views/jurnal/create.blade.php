@extends('layouts.app')

@section('content')
    <div class="col-lg-8 mb-10 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4 class="m-15 font-weight-bold">{{ __('Tambah Entri Jurnal') }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('jurnal.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="keterangan">Keterangan:</label>
                        <input type="text" name="keterangan" class="form-control" id="keterangan"
                            placeholder="Masukkan keterangan" required>
                    </div>
                    <div class="form-group">
                        <label for="detail_transaksi_id">Pilih Satu Transaksi:</label>
                        <select name="detail_transaksi_id" class="form-control" id="detail_transaksi_id" onchange="updateJumlah()" required>
                            <option value="">--- Pilih Transaksi ---</option>


                            @foreach ($detailTransaksis as $detailTransaksi)
                                @if ($detailTransaksi->transaksiMasuk)
                                    <option value="{{ $detailTransaksi->id }}" data-jumlah="{{ $detailTransaksi->transaksiMasuk->total }}">
                                        {{ $detailTransaksi->transaksiMasuk->no_trans }} | {{ $detailTransaksi->transaksiMasuk->keterangan }} |  {{ $detailTransaksi->transaksiMasuk->tgl }} | Rp. {{ number_format($detailTransaksi->transaksiMasuk->total),'2',',','.' }} </option>
                                @endif
                            @endforeach


                            @foreach ($detailTransaksis as $detailTransaksi)
                                @if ($detailTransaksi->transaksiKeluar)
                                    <option value="{{ $detailTransaksi->id }}" data-jumlah="{{ $detailTransaksi->transaksiKeluar->total }}">
                                        {{ $detailTransaksi->transaksiKeluar->no_trans }} | {{ $detailTransaksi->transaksiKeluar->keterangan }} |  {{ $detailTransaksi->transaksiKeluar->tgl }} | Rp. {{ number_format($detailTransaksi->transaksiKeluar->total),'2',',','.' }} </option>
                                @endif
                            @endforeach


                        </select>
                    </div>

                    <div class="form-group">
                        <label for="debit">Akun Debit:</label>
                        <select name="debit" class="form-control" id="debit" required>
                            <option value="">Pilih Akun</option>
                            @foreach ($akuns as $akun)
                                <option value="{{ $akun->nm_akun }}">{{ $akun->nm_akun }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="kredit">Akun Kredit:</label>
                        <select name="kredit" class="form-control" id="kredit" required>
                            <option value="">Pilih Akun</option>
                            @foreach ($akuns as $akun)
                                <option value="{{ $akun->nm_akun }}">{{ $akun->nm_akun }}</option>
                            @endforeach
                        </select>
                    </div>




                    <div class="form-group">
                        <label for="total">Total:</label>
                        <input type="text" name="total" class="form-control" id="total" step="0.01" required readonly>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
<script>
    function updateJumlah() {
        const transaksiSelect = document.getElementById('detail_transaksi_id');
        const selectedTransaksi = transaksiSelect.options[transaksiSelect.selectedIndex];
        const jumlah = selectedTransaksi.getAttribute('data-jumlah');
        document.getElementById('total').value = jumlah;
    }
</script>
