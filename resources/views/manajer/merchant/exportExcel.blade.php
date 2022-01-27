<?php
header('Content-type:application/octet-stream/');
header('Content-Disposition:attachment; filename=data_merchant.xls');
header('Pragma: no-active');
header('Expires: 0');
?>

<table border="1" width="100%">
    <thead>
        <tr>
            <th>No</th>
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
            <tr width="60" height="60" style="text-align: center;">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $value->salesHasMerchant->userSales->nama_user ?? '' }}</td>
                <td>{{ $value->nama_merchant ?? '' }}</td>
                <td>{{ $value->kategori->nama_kategori ?? '' }}</td>
                <td>{{ $value->nama_pemilik ?? '' }}</td>
                <td>{{ $value->nomor_telepon ?? '' }}</td>
                <td>{{ $value->alamat ?? '' }}</td>
                <td>{{ $value->province->name ?? '' }}</td>
                <td>{{ $value->city->name ?? '' }}</td>
                <td>{{ $value->district->name ?? '' }}</td>
                <td><img src="{{ asset('storage/merchant/' . $value->foto) ?? '' }}" alt="" width="60" height="60">
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
