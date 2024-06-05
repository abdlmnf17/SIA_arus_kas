@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
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
                            <input type="text" name="no_trans" id="no_trans" class="form-control" value="{{ old('no_trans') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="tgl">Tanggal:</label>
                            <input type="date" name="tgl" id="tgl" class="form-control" value="{{ old('tgl') }}">
                        </div>

                        <div class="row mb-3">
                            <label for="pasien_id" class="col-md-4 col-form-label text-md-end">Pasien</label>
                            <div class="col-md-6">
                                <input type="text" id="nm_pasien" class="form-control" name="nm_pasien" autocomplete="off" placeholder="Masukkan Nama Pasien" required>
                                <div id="pasienList" class="dropdown-menu"></div>

                                <input type="hidden" name="pasien_id" id="pasien_id">
                            </div>
                        </div>

                        <div id="obat-container">
                            <div class="row mb-3 obat-row">
                                <label for="obat_1" class="col-md-4 col-form-label text-md-end">Obat 1</label>
                                <div class="col-md-4">
                                    <select id="obat_1" class="form-control obat-select" name="obat_ids[]" data-harga="0" required>
                                        <option value="">Pilih Obat</option>
                                        @foreach ($obat as $o)
                                            <option value="{{ $o->id }}" data-harga="{{ $o->harga }}">
                                                {{ $o->nm_obat }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="jumlah[]" class="form-control jumlah-obat" placeholder="Jumlah" min="1" value="1" required>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-primary" id="addObat">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="keterangan">Keterangan:</label>
                            <input type="text" name="keterangan" id="keterangan" class="form-control" value="{{ old('keterangan') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="jumlah_periksa">Harga Periksa:</label>
                            <input type="number" id="jumlah_periksa" class="form-control" placeholder="Masukan harga periksa pasien" min="0" step="0.01">
                        </div>

                        <div class="form-group">
                            <label for="total">Subtotal:</label>
                            <input type="number" name="total" id="total" class="form-control" value="{{ old('total') }}" required>
                        </div>

                        <button type="button" id="showConfirmModal" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
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
        var pasienList = document.getElementById('pasienList');
        var pasienIdInput = document.getElementById('pasien_id');
        var showConfirmModalButton = document.getElementById('showConfirmModal');
        var confirmSubmitButton = document.getElementById('confirmSubmit');
        var transaksiForm = document.getElementById('transaksiForm');

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
                            pasienList.innerHTML = '<div class="list-group-item">Tidak ditemukan</div>';
                        }
                    });
            } else {
                pasienList.innerHTML = '';
            }
        });

        pasienList.addEventListener('click', function(e) {
            if (e.target && e.target.nodeName == "A") {
                nmPasienInput.value = e.target.textContent;
                pasienIdInput.value = e.target.dataset.id;
                pasienList.style.display = 'none';
            }
        });

        // Additional code for adding and removing obat rows, and updating total
        var obatContainer = document.getElementById('obat-container');
        var addButtonObat = document.getElementById('addObat');
        var obatCount = 1;

        addButtonObat.addEventListener('click', function() {
            obatCount++;
            var newRow = document.createElement('div');
            newRow.classList.add('row', 'mb-3', 'obat-row');
            newRow.innerHTML = `
                <label for="obat_${obatCount}" class="col-md-4 col-form-label text-md-end">Obat ${obatCount}</label>
                <div class="col-md-4">
                    <select id="obat_${obatCount}" class="form-control obat-select" name="obat_ids[]" data-harga="0" required>
                        <option value="">Pilih Obat</option>
                        @foreach ($obat as $o)
                            <option value="{{ $o->id }}" data-harga="{{ $o->harga }}">
                                {{ $o->nm_obat }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" name="jumlah[]" class="form-control jumlah-obat" placeholder="Jumlah" min="1" value="1" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger" onclick="removeObat(this)"><i class="fas fa-trash"></i></button>
                </div>
            `;
            obatContainer.appendChild(newRow);
            attachEventListeners(newRow);
        });

        window.removeObat = function(btn) {
            btn.closest('.obat-row').remove();
            updateTotal();
        };

        function attachEventListeners(row) {
            row.querySelector('.obat-select').addEventListener('change', updateTotal);
            row.querySelector('.jumlah-obat').addEventListener('input', updateTotal);
        }

        function updateTotal() {
            var total = 0;
            var obatRows = document.querySelectorAll('.obat-row');
            obatRows.forEach(function(row) {
                var obatSelect = row.querySelector('.obat-select');
                var jumlahInput = row.querySelector('.jumlah-obat');
                var harga = parseFloat(obatSelect.options[obatSelect.selectedIndex].getAttribute('data-harga')) || 0;
                var jumlah = parseFloat(jumlahInput.value) || 0;
                total += harga * jumlah;
            });

            var jumlahPeriksa = parseFloat(document.getElementById('jumlah_periksa').value) || 0;
            total += jumlahPeriksa;

            document.getElementById('total').value = total.toFixed(2);
        }

        // Attach event listeners to the initial row and jumlah periksa
        attachEventListeners(document.querySelector('.obat-row'));
        document.getElementById('jumlah_periksa').addEventListener('input', updateTotal);

        // Show confirmation modal
        showConfirmModalButton.addEventListener('click', function() {
            $('#confirmModal').modal('show');
        });

        // Submit form on confirm
        confirmSubmitButton.addEventListener('click', function() {
            transaksiForm.submit();
        });
    });
</script>
@endsection
