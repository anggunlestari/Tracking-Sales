<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Merchant</title>
</head>

<body onload="window.print()">
    <table border="1" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Manajer</th>
                <th>Nama Sales</th>
                <th>Nama Merchant/Partner</th>
                <th>Kategori</th>
                <th>Nama Pemilik</th>
                <th>Nomor Telepon</th>
                <th>Alamat</th>
                <th>Provinsi</th>
                <th>Kabupaten</th>
                <th>Kecamatan</th>
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            @php($no = 1)
            @foreach ($merchant as $value)
                <tr style="text-align: center;">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $value->manajerHasMerchant->userManajer->nama_user ?? '' }}</td>
                    <td>{{ $value->salesHasMerchant->userSales->nama_user ?? '' }}</td>
                    <td>{{ $value->nama_merchant ?? '' }}</td>
                    <td>{{ $value->kategori->nama_kategori ?? '' }}</td>
                    <td>{{ $value->nama_pemilik ?? '' }}</td>
                    <td>{{ $value->nomor_telepon ?? '' }}</td>
                    <td>{{ $value->alamat ?? '' }}</td>
                    <td>{{ $value->province->name ?? '' }}</td>
                    <td>{{ $value->city->name ?? '' }}</td>
                    <td>{{ $value->district->name ?? '' }}</td>
                    <td><img src="{{ asset('storage/merchant/' . $value->foto) ?? '' }}" alt="" width="60"
                            height="60">
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
