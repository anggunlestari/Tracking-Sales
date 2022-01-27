<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Area Lokasi</title>
</head>

<body onload="window.print()">
    <table border="1" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Area Lokasi</th>
                <th>Provinsi</th>
                <th>Kabupaten</th>
                <th>Kecamatan</th>
            </tr>
        </thead>
        <tbody>
            @php($no = 1)
            @foreach ($area_lokasi as $value)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $value->nama_area ?? '' }}</td>
                    <td>{{ $value->province->name ?? '' }}</td>
                    <td>{{ $value->city->name ?? '' }}</td>
                    <td>{{ $value->district->name ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
