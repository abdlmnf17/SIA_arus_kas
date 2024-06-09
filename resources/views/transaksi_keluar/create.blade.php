@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <a href="{{ route('transaksi_keluar.index') }}" class="btn btn-primary float-right">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h4 class="m-15 font-weight-bold">TAMBAH TRANSAKSI KELUAR</h4>
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

                        <form action="{{ route('transaksi_keluar.store') }}" method="POST" id="transaksiForm">
                            @csrf

                            <div class="form-group">
                                <label for="no_trans">No Transaksi:</label>
                                <input type="text" name="no_trans" id="no_trans" class="form-control"
                                    value="{{ old('no_trans') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="tgl">Tanggal:</label>
                                <input type="date" name="tgl" id="tgl" class="form-control"
                                    value="{{ old('tgl') }}">
                            </div>

                            <div class="row mb-3">
                                <label for="pemasok_id" class="col-md-4 col-form-label text-md-end">Pemasok</label>
                                <div class="col-md-6">
                                    <input type="text" id="nm_pemasok" class="form-control" name="nm_pemasok"
                                        autocomplete="off" placeholder="Masukkan Nama Pemasok" required>
                                    <div id="pemasokList" class="dropdown-menu"></div>

                                    <input type="hidden" name="pemasok_id" id="pemasok_id">
                                </div>
                            </div>

                            <div id="barang-container">
                                <div class="row mb-3 barang-row">
                                    <label for="barang_1" class="col-md-4 col-form-label text-md-end">Barang 1</label>
                                    <div class="col-md-4">
                                        <select id="barang_1" class="form-control barang-select" name="barang_ids[]"
                                            data-harga="0" required>
                                            <option value="">Pilih Barang</option>
                                            @foreach ($barang as $b)
                                                <option value="{{ $b->id }}" data-harga="{{ $b->harga }}">
                                                    {{ $b->nm_brg }} | Rp. {{ number_format($b->harga), '2',',','.' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="jumlahBarang[]" class="form-control jumlah-barang"
                                            placeholder="Jumlah" min="1" value="1" required>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-primary" id="addBarang">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Obat -->
                            <div id="obat-container">
                                <div class="row mb-3 obat-row">
                                    <label for="obat_1" class="col-md-4 col-form-label text-md-end">Obat 1</label>
                                    <div class="col-md-4">
                                        <select id="obat_1" class="form-control obat-select" name="obat_ids[]"
                                            data-harga="0" required>
                                            <option value="">Pilih Obat</option>
                                            @foreach ($obat as $o)
                                                <option value="{{ $o->id }}" data-harga="{{ $o->harga }}">
                                                    {{ $o->nm_obat }} | Rp. {{ number_format($o->harga), '2',',','.' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="jumlah[]" class="form-control jumlah-obat"
                                            placeholder="Jumlah" min="1" value="1" required>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-primary" id="addObat">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- End Form Obat -->

                            <div class="form-group">
                                <label for="keterangan">Keterangan:</label>
                                <input type="text" name="keterangan" id="keterangan" class="form-control"
                                    value="{{ old('keterangan') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="jumlah_periksa">Harga Lainnya:</label>
                                <input type="number" id="jumlah_periksa" class="form-control"
                                    placeholder="Contoh: harga pajak, dan lain lain (ketik 0 jika tidak ada)"
                                    min="0" step="0.01">
                            </div>

                            <div class="form-group">
                                <label for="total">Subtotal:</label>
                                Rp. <input type="number" name="total" id="total" class="form-control"
                                    value="{{ old('total') }}" required>
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
            var nmPemasokInput = document.getElementById('nm_pemasok');
            var pemasokList = document.getElementById('pemasokList');
            var pemasokIdInput = document.getElementById('pemasok_id');
            var showConfirmModalButton =
                document.getElementById('showConfirmModal');
            var confirmSubmitButton = document.getElementById('confirmSubmit');
            var transaksiForm = document.getElementById('transaksiForm');

            nmPemasokInput.addEventListener('keyup', function() {
                var query = nmPemasokInput.value;
                if (query.length > 1) {
                    fetch(`/search-pemasok?query=${query}`)
                        .then(response => response.json())
                        .then(data => {
                            pemasokList.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(pemasok => {
                                    var option = document.createElement('a');
                                    option.classList.add('dropdown-item');
                                    option.href = '#';
                                    option.textContent = pemasok.nm_pemasok;
                                    option.dataset.id = pemasok.id;
                                    pemasokList.appendChild(option);
                                });
                                pemasokList.style.display = 'block';
                            } else {
                                pemasokList.innerHTML =
                                    '<div class="list-group-item">Tidak ditemukan</div>';
                            }
                        });
                } else {
                    pemasokList.innerHTML = '';
                }
            });

            pemasokList.addEventListener('click', function(e) {
                if (e.target && e.target.nodeName == "A") {
                    nmPemasokInput.value = e.target.textContent;
                    pemasokIdInput.value = e.target.dataset.id;
                    pemasokList.style.display = 'none';
                }
            });

            // Additional code for adding and removing barang rows, and updating total
            var barangContainer = document.getElementById('barang-container');
            var addButtonBarang = document.getElementById('addBarang');
            var barangCount = 1;

            addButtonBarang.addEventListener('click', function() {
                barangCount++;
                var newRow = document.createElement('div');
                newRow.classList.add('row', 'mb-3', 'barang-row');
                newRow.innerHTML = `
                <label for="barang_${barangCount}" class="col-md-4 col-form-label text-md-end">Barang ${barangCount}</label>
                <div class="col-md-4">
                    <select id="barang_${barangCount}" class="form-control barang-select" name="barang_ids[]" data-harga="0" required>
                        <option value="">Pilih Barang</option>
                        @foreach ($barang as $b)
                            <option value="{{ $b->id }}" data-harga="{{ $b->harga }}">
                                {{ $b->nm_brg }} | {{ $b->harga }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" name="jumlahBarang[]" class="form-control jumlah-barang" placeholder="Jumlah" min="1" value="1" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger" onclick="removeBarang(this)"><i class="fas fa-trash"></i></button>
                </div>
            `;
                barangContainer.appendChild(newRow);
                attachEventListeners(newRow);
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
                                {{ $o->nm_obat }} | {{ $o->harga }}
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
                attachEventListenersObat(newRow);
            });

            window.removeObat = function(btn) {
                btn.closest('.obat-row').remove();
                updateTotal();
            };

            function attachEventListenersObat(row) {
                row.querySelector('.obat-select').addEventListener('change', updateTotal);
                row.querySelector('.jumlah-obat').addEventListener('input', updateTotal);
            }

            // Attach event listeners to the initial row and jumlah periksa
            attachEventListeners(document.querySelector('.barang-row'));
            attachEventListenersObat(document.querySelector('.obat-row'));
            document.getElementById('jumlah_periksa').addEventListener('input', updateTotal);


            window.removeBarang = function(btn) {
                btn.closest('.barang-row').remove();
                updateTotal();
            };

            function attachEventListeners(row) {
                row.querySelector('.barang-select').addEventListener('change', updateTotal);
                row.querySelector('.jumlah-barang').addEventListener('input', updateTotal);
            }





            function updateTotal() {
                var total = 0;
                var barangRows = document.querySelectorAll('.barang-row');
                barangRows.forEach(function(row) {
                    var barangSelect = row.querySelector('.barang-select');
                    var jumlahInput = row.querySelector('.jumlah-barang');
                    var harga = parseFloat(barangSelect.options[barangSelect.selectedIndex].getAttribute(
                        'data-harga')) || 0;
                    var jumlahBarang = parseFloat(jumlahInput.value) || 0;
                    total += harga * jumlahBarang;
                });

                var obatRows = document.querySelectorAll('.obat-row');
                obatRows.forEach(function(row) {
                    var obatSelect = row.querySelector('.obat-select');
                    var jumlahInput = row.querySelector('.jumlah-obat');
                    var harga = parseFloat(obatSelect.options[obatSelect.selectedIndex].getAttribute(
                        'data-harga')) || 0;
                    var jumlah = parseFloat(jumlahInput.value) || 0;
                    total += harga * jumlah;
                });

                var jumlahPeriksa = parseFloat(document.getElementById('jumlah_periksa').value) || 0;
                total += jumlahPeriksa;

                document.getElementById('total').value = total.toFixed(2);
            }




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
