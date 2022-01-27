<?php
header('Content-type:application/octet-stream/');
header('Content-Disposition:attachment; filename=data_area_lokasi.xls');
header('Pragma: no-active');
header('Expires: 0');
?>

<table border="1" width="100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Area Lokasi</th>
            <th>Provinsi</th>
            <th>Kecamatan</th>
            <th>Kabupaten</th>
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
