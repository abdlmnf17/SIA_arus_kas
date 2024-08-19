@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <a href="{{ route('transaksi_masuk.index') }}" class="btn btn-primary float-right">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h4 class="m-15 font-weight-bold">TAMBAH TRANSAKSI MASUK</h4>
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

                    <form action="{{ route('transaksi_masuk.store') }}" method="POST" id="transaksiForm">
                        @csrf

                        <div class="form-group">
                            <label for="no_trans">No Transaksi:</label>
                            <input type="hidden" name="no_trans_generated" id="no_trans_generated"
                                value="{{ old('no_trans_generated') }}">
                            <input type="text" name="no_trans" id="no_trans" class="form-control"
                                value="{{ $no_trans }}" required>
                        </div>

                        <div class="form-group">
                            <label for="tgl">Tanggal:</label>
                            <input type="date" name="tgl" id="tgl" class="form-control"
                                value="{{ old('tgl') }}">
                        </div>

                        <div class="row mb-3">
                            <label for="pasien_id" class="col-md-4 col-form-label text-md-end">Pasien</label>
                            <div class="col-md-6">
                                <input type="text" id="nm_pasien" class="form-control" name="nm_pasien"
                                    autocomplete="off" placeholder="Masukkan Nama Pasien" required>
                                <div id="pasienList" class="dropdown-menu" style="display: none;"></div>
                                <input type="hidden" name="pasien_id" id="pasien_id">
                            </div>
                        </div>

                        <div id="obat-container">
                            <!-- Header tabel akan dimasukkan di sini -->
                        </div>

                        <div class="form-group">
                            <label for="keterangan">Keterangan:</label>
                            <input type="text" name="keterangan" id="keterangan" class="form-control"
                                value="{{ old('keterangan') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="keterangan_dosis">Keterangan Dosis:</label>
                            <input type="text" name="keterangan_dosis" id="keterangan_dosis" class="form-control"
                                value="{{ old('keterangan_dosis') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="harga_periksa">Harga Periksa:</label>
                            <input type="number" id="harga_periksa" name="harga_periksa" class="form-control"
                                placeholder="Masukkan harga periksa pasien" min="0" step="0.01" required>
                        </div>

                        <div class="form-group">
                            <label for="total">Subtotal:</label>
                            <input type="number" name="total" id="total" class="form-control"
                                value="{{ old('total') }}" required readonly>
                        </div>

                        <button type="button" id="showConfirmModal" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah data sudah benar diisi?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmSubmit">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var nmPasienInput = document.getElementById('nm_pasien');
        var obatContainer = document.getElementById('obat-container');
        var pasienList = document.getElementById('pasienList');
        var pasienIdInput = document.getElementById('pasien_id');
        var showConfirmModalButton = document.getElementById('showConfirmModal');
        var confirmSubmitButton = document.getElementById('confirmSubmit');
        var transaksiForm = document.getElementById('transaksiForm');
        var hargaPeriksaInput = document.getElementById('harga_periksa');
        var totalInput = document.getElementById('total');

        nmPasienInput.addEventListener('keyup', function() {
            var query = nmPasienInput.value;
            if (query.length > 1) {
                fetch(`/search-pasien?query=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        pasienList.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(pasien => {
                                var option = document.createElement('a');
                                option.classList.add('dropdown-item');
                                option.href = '#';
                                option.textContent = pasien.nm_pasien;
                                option.dataset.id = pasien.id;
                                pasienList.appendChild(option);
                            });
                            pasienList.style.display = 'block';
                        } else {
                            pasienList.innerHTML =
                                '<div class="list-group-item">Tidak ditemukan</div>';
                        }
                    });
            } else {
                pasienList.innerHTML = '';
                pasienList.style.display = 'none';
            }
        });

        pasienList.addEventListener('click', function(e) {
            if (e.target && e.target.nodeName == "A") {
                nmPasienInput.value = e.target.textContent;
                pasienIdInput.value = e.target.dataset.id;
                pasienList.style.display = 'none';
                loadPasienDetails(e.target.dataset.id);
            }
        });

        function loadPasienDetails(pasienId) {
            fetch(`/pasien-details/${pasienId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('keterangan_dosis').value = data.keterangan || '';
                    loadDetailObat(pasienId);
                });
        }

        function loadDetailObat(id) {
            fetch(`/detail-obat/${id}`)
                .then(response => response.json())
                .then(data => {
                    obatContainer.innerHTML = ''; // Clear existing rows

                    // Adding the header row
                    var headerRow = document.createElement('div');
                    headerRow.classList.add('row', 'mb-3');
                    headerRow.innerHTML = `
                        <div class="col-md-4 font-weight-bold">Nama Obat</div>
                        <div class="col-md-4 font-weight-bold">Jumlah</div>
                        <div class="col-md-2 font-weight-bold">Harga Satuan</div>
                        <div class="col-md-2 font-weight-bold">Total</div>
                    `;
                    obatContainer.appendChild(headerRow);

                    var total = 0;

                    data.forEach((item, index) => {
                        var newRow = document.createElement('div');
                        newRow.classList.add('row', 'mb-3');
                        newRow.innerHTML = `
                            <div class="col-md-4">
                                <label class="col-form-label">${item.nm_obat}</label>
                            </div>
                            <div class="col-md-4">
                                <input type="number" class="form-control jumlah-obat" value="${item.jumlah}" readonly>
                            </div>
                            <div class="col-md-2">
                                <input type="number" class="form-control harga-obat" value="${item.harga}" readonly>
                            </div>
                            <div class="col-md-2">
                                <input type="number" class="form-control subtotal-obat" value="${item.jumlah * item.harga}" readonly>
                            </div>
                        `;
                        obatContainer.appendChild(newRow);
                        total += item.jumlah * item.harga;
                    });

                    // Update total input field
                    updateTotal();
                });
        }

        function updateTotal() {
            var hargaPeriksa = parseFloat(hargaPeriksaInput.value) || 0;
            var subtotal = Array.from(document.querySelectorAll('.subtotal-obat'))
                .reduce((sum, input) => sum + parseFloat(input.value || 0), 0);
            totalInput.value = (subtotal + hargaPeriksa).toFixed(2);
        }

        // Event listener for Harga Periksa input field
        hargaPeriksaInput.addEventListener('input', updateTotal);

        // Event listener for changes in the obat table
        obatContainer.addEventListener('change', function(e) {
            if (e.target && (e.target.classList.contains('jumlah-obat') || e.target.classList.contains('harga-obat'))) {
                updateSubtotal();
                updateTotal();
            }
        });

        function updateSubtotal() {
            var rows = obatContainer.querySelectorAll('.row.mb-3');
            rows.forEach(row => {
                var jumlah = parseFloat(row.querySelector('.jumlah-obat').value) || 0;
                var harga = parseFloat(row.querySelector('.harga-obat').value) || 0;
                var subtotal = jumlah * harga;
                row.querySelector('.subtotal-obat').value = subtotal.toFixed(2);
            });
        }

        showConfirmModalButton.addEventListener('click', function() {
            $('#confirmModal').modal('show');
        });

        confirmSubmitButton.addEventListener('click', function() {
            transaksiForm.submit();
        });
    });
</script>
@endsection
