<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            color: #333;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #e7e7e7;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
        }
        .header p {
            margin: 0;
            color: #777;
        }
        .details {
            margin-bottom: 20px;
        }
        .details p {
            margin: 5px 0;
        }
        .details strong {
            display: inline-block;
            width: 150px;
        }
        .table-container {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ffffff;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #ffffff;
        }
        .total {
            text-align: right;
            margin-right: 10px;
            font-size: 1.2em;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9em;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Invoice Transaksi</h1>
            <h2>{{ config('app.perusahaan', 'Laravel') }}</h2><br/>
        </div>

        <div class="details">
            <p><strong>No Transaksi:</strong> {{ $transaksi->no_trans }}</p>
            <p><strong>Tanggal:</strong> {{ $transaksi->tgl }}</p>
            <p><strong>Nama Pasien:</strong> {{ $transaksi->pasien->nm_pasien }}</p>

        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Keterangan</th>


                    </tr>
                </thead>
                <tbody>

                    <tr>


                        <td>{{ $transaksi->keterangan }}</td>


                    </tr>

                </tbody>
            </table>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nama Obat</th>

                        <th>Harga Satuan</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi->detailMasuk as $detail)
                    <tr>

                        <td>{{ $detail->obat->nm_obat }}</td>

                        <td>{{ number_format($detail->obat->harga, 2) }}</td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="total">
            <p><strong>Total: </strong> Rp.<u>{{ number_format($transaksi->total, 2) }}</u></p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.perusahaan', 'Laravel') }}.</p>
        </div>
    </div>
</body>
</html>
