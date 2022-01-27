<?php
header('Content-type:application/octet-stream/');
header('Content-Disposition:attachment; filename=backup_data_manajer.xls');
header('Pragma: no-active');
header('Expires: 0');
?>

<table border="1" width="100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Area Lokasi</th>
            <th>Nama Manajer</th>
            <th>Nomor Telepon</th>
            <th>Email</th>
            <th>Foto</th>
        </tr>
    </thead>
    <tbody>
        @php($no = 1)
        @foreach ($manajer as $value)
            <tr width="60" height="60" style="text-align: center;">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $value->area_lokasi->nama_area ?? '' }}</td>
                <td>{{ $value->nama_user ?? '' }}</td>
                <td>{{ $value->nomor_telepon ?? '' }}</td>
                <td>{{ $value->email ?? '' }}</td>
                <td><img src="{{ asset('storage/manajer/' . $value->foto) ?? '' }}" alt="" width="60" height="60">
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
