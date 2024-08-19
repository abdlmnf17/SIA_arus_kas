@extends('layouts.app')

@section('content')

    @php
        $role = auth()->user()->role;
    @endphp

    @if ($role === 'admin')
        <div class="col-lg-8 mb-10 mx-auto">
            <!-- Project Card Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <a href="{{ route('pasien.index') }}" class="btn btn-primary float-right">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h4 class="m-15 font-weight-bold">EDIT PASIEN</h4>
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

                    <form action="{{ route('pasien.update', $pasien->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="nm_pasien">Nama Pasien:</label>
                            <input type="text" class="form-control" id="nm_pasien" name="nm_pasien"
                                value="{{ $pasien->nm_pasien }}" required>
                        </div>
                        <div class="form-group">
                            <label for="umur">Umur:</label>
                            <input type="text" class="form-control" id="umur" name="umur"
                                value="{{ $pasien->umur }}" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat:</label>
                            <input type="text" class="form-control" id="alamat" name="alamat"
                                value="{{ $pasien->alamat }}" required>
                        </div>
                        <div class="form-group">
                            <label for="tensi">Tensi:</label>
                            <input type="text" class="form-control" id="tensi" name="tensi"
                                value="{{ $pasien->tensi }}" required>
                        </div>
                        <div class="form-group">
                            <label for="keluhan">Keluhan:</label>
                            <input type="text" class="form-control" id="tensi" name="keluhan"
                                value="{{ $pasien->keluhan }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>

    @endif


    @if ($role === 'pemeriksa')
        <div class="col-lg-8 mb-10 mx-auto">
            <!-- Project Card Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <a href="/periksa-pasien" class="btn btn-primary float-right">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h4 class="m-15 font-weight-bold">KETERANGAN PASIEN</h4>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('pasien.pemeriksa', $pasien->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="nm_pasien">Nama Pasien:</label>
                            <input type="text" class="form-control" id="nm_pasien" name="nm_pasien"
                                value="{{ $pasien->nm_pasien }}" required>
                        </div>
                        <div class="form-group">
                            <label for="umur">Umur:</label>
                            <input type="text" class="form-control" id="umur" name="umur"
                                value="{{ $pasien->umur }}" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat:</label>
                            <input type="text" class="form-control" id="alamat" name="alamat"
                                value="{{ $pasien->alamat }}" required>
                        </div>
                        <div class="form-group">
                            <label for="tensi">Tensi:</label>
                            <input type="text" class="form-control" id="tensi" name="tensi"
                                value="{{ $pasien->tensi }}" required>
                        </div>
                        <div class="form-group">
                            <label for="keluhan">Keluhan:</label>
                            <input type="text" class="form-control" id="tensi" name="keluhan"
                                value="{{ $pasien->keluhan }}" required>
                        </div>
                        <div class="form-group">
                            <label for="diagnosa">Diagnosa:</label>
                            <input type="text" class="form-control" id="diagnosa" name="diagnosa"
                                value="{{ $pasien->diagnosa }}" required>
                        </div>
                        <div class="form-group">
                            <label for="keterangan_dosis">Keterangan Dosis:</label>
                            <input type="text" class="form-control" id="keterangan_dosis" name="keterangan_dosis"
                                value="{{ $pasien->keterangan_dosis }}" required>
                        </div>

                        <div id="obat-container">
                            <div class="row mb-3 obat-row">
                                <label for="obat_1" class="col-md-4 col-form-label text-md-end">Obat 1</label>
                                <div class="col-md-4">
                                    <select id="obat_1" class="form-control obat-select" name="obat_ids[]"
                                        data-harga="0" required>
                                        <option value="">Pilih Obat</option>
                                        @foreach ($obat as $o)
                                            <option value="{{ $o->id }}" data-harga="{{ $o->harga }}">
                                                {{ $o->nm_obat }}
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
                        <div class="form-group">
                            <label for="total">Subtotal:</label>
                            <input type="number" name="total" id="total" class="form-control"
                                value="{{ old('total') }}" required>
                        </div>


                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>


                </div>
            </div>
        </div>

        <div class="col-lg-8 mb-10 mx-auto">
            <!-- Project Card Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">

                    <h4 class="m-15 font-weight-bold">DETAIL PEMERIKSAAN</h4>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Obat</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detailPeriksa as $detail)
                            <tr>
                                <td>{{ $detail->obat->nm_obat ?? 'Tidak Diketahui' }}</td>
                                <td>{{ $detail->jumlah }}</td>
                                <td>{{ $detail->obat->harga ?? 'Tidak Ada' }}</td>
                                <td>
                                    <form action="{{ route('detailperiksa.destroy', $detail->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody><br/>
                </table>
                    {{-- Total: {{$detail->pasien->total ?? 'tidak ada'}}
            </div> --}}
        </div>



    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to update total based on selected obat and quantity
            function updateTotal() {
                var total = 0;
                var obatRows = document.querySelectorAll('.obat-row');
                obatRows.forEach(function(row) {
                    var obatSelect = row.querySelector('.obat-select');
                    var jumlahInput = row.querySelector('.jumlah-obat');
                    var harga = parseFloat(obatSelect.options[obatSelect.selectedIndex].getAttribute(
                        'data-harga')) || 0;
                    var jumlah = parseFloat(jumlahInput.value) || 0;
                    total += harga * jumlah;
                });
                document.getElementById('total').value = total.toFixed(2);
            }

            // Add event listeners to existing rows
            function attachEventListeners(row) {
                row.querySelector('.obat-select').addEventListener('change', updateTotal);
                row.querySelector('.jumlah-obat').addEventListener('input', updateTotal);
            }

            // Initialize event listeners on existing rows
            document.querySelectorAll('.obat-row').forEach(attachEventListeners);

            // Handle adding new obat rows
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
                updateTotal(); // Update total after adding new row
            });

            // Remove obat row
            window.removeObat = function(btn) {
                btn.closest('.obat-row').remove();
                updateTotal(); // Update total after removing row
            };
        });
    </script>

@endsection
