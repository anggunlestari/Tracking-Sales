<?php
header('Content-type:application/octet-stream/');
header('Content-Disposition:attachment; filename=history_merchant.xls');
header('Pragma: no-active');
header('Expires: 0');
?>

<h3 style="text-align: center;">History Kunjungan {{ $history[0]->aktivitas->merchant->nama_merchant }}</h3>
<table border="1" width="100%">
    <thead>
        <tr>
            <th>No</th>h>
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
                <td><img src="{{ asset('storage/aktivitas/' . $value->foto) ?? '' }}" alt="" width="60" height="60">
            </tr>
        @endforeach
    </tbody>
</table>
