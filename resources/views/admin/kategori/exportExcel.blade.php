<?php
header('Content-type:application/octet-stream/');
header('Content-Disposition:attachment; filename=data_kategori.xls');
header('Pragma: no-active');
header('Expires: 0');
?>

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
