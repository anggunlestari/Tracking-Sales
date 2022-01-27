<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Sales</title>
</head>

<body onload="window.print()">
    <table border="1" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Manajer</th>
                <th>Nama Sales</th>
                <th>Nomor Telepon</th>
                <th>Email</th>
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            @php($no = 1)
            @foreach ($user as $value)
                <tr width="60" height="60" style="text-align: center;">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $value->manajerHasSales->userManajer->nama_user ?? '' }}</td>
                    <td>{{ $value->nama_user ?? '' }}</td>
                    <td>{{ $value->nomor_telepon ?? '' }}</td>
                    <td>{{ $value->email ?? '' }}</td>
                    <td><img src="{{ asset('storage/sales/' . $value->foto) ?? '' }}" alt="" width="60" height="60">
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
