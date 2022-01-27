<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>History Merchant</title>
</head>

<body onload="window.print()">
    <h3 style="text-align: center;">History Merchant {{ $history[0]->aktivitas->merchant->nama_merchant }}</h3>
    <table border="1" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Sales</th>
                <th>Status</th>
                <th>Keterangan</th>
                <th>Nominal</th>
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            @php($no = 1)
                @foreach ($history as $value)
                    <tr width="70" height="70" style="text-align: center;">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($value->updated_at)->format('d-m-Y') ?? '' }}</td>
                        <td>{{ $value->aktivitas->merchant->salesHasMerchant->userSales->nama_user ?? '' }}</td>
                        <td>{{ $value->status->nama_status ?? '' }}</td>
                        <td>{{ $value->keterangan ?? '' }}</td>
                        <td>{{ \App\Utilities\Helpers::formatCurrency($value->nominal, 'Rp' ?? '') }}
                        </td>
                        <td><img src="{{ asset('storage/aktivitas/' . $value->foto) ?? '' }}" alt="" width="60"
                                height="60">
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>

    </html>
