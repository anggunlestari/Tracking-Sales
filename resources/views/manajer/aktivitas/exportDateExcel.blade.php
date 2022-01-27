<?php
header('Content-type:application/octet-stream/');
header('Content-Disposition:attachment; filename=aktivitas_sales.xls');
header('Pragma: no-active');
header('Expires: 0');
?>

<h4 style="text-align: center;">Dari Tanggal : {{ \Carbon\Carbon::parse($tglawal)->format('d-m-Y') ?? '' }} Sampai
    Tanggal : {{ \Carbon\Carbon::parse($tglakhir)->format('d-m-Y') ?? '' }}</h4>
<table border="1" width="100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Nama Sales</th>
            <th>Nama Merchant/Partner</th>
            <th>Status</th>
            <th>Keterangan</th>
            <th>Nominal</th>
            <th>Foto</th>
        </tr>
    </thead>
    <tbody>
        @php($no = 1)
        @foreach ($cetak as $value)
            <tr width="60" height="60" style="text-align: center;">
                <td>{{ $loop->iteration }}</td>
                <td>{{ \Carbon\Carbon::parse($value->tanggal)->format('d-m-Y') ?? '' }}</td>
                <td>{{ $value->aktivitas->merchant->salesHasMerchant->userSales->nama_user ?? '' }}</td>
                <td>{{ $value->aktivitas->merchant->nama_merchant ?? '' }}</td>
                <td>{{ $value->status->nama_status ?? '' }}</td>
                <td>{{ $value->keterangan ?? '' }}</td>
                <td>{{ \App\Utilities\Helpers::formatCurrency($value->nominal, 'Rp' ?? '') ?? '' }}
                </td>
                <td><img src="{{ asset('storage/aktivitas/' . $value->foto) ?? '' }}" alt="" width="60" height="60">
            </tr>
        @endforeach
    </tbody>
</table>
