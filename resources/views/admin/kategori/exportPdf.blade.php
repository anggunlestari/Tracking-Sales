<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Kategori</title>
</head>

<body onload="window.print()">
    <table border="1" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @php($no = 1)
            @foreach ($kategori as $value)
                <tr style="text-align: center;">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $value->nama_kategori ?? '' }}</td>
                    <td>{{ $value->deskripsi ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
