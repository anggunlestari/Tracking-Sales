<?php
header('Content-type:application/octet-stream/');
header('Content-Disposition:attachment; filename=laporan_statistik_sales.xls');
header('Pragma: no-active');
header('Expires: 0');
?>

<table border="1" width="100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Sales</th>
            <th>Total Merchant/Partner</th>
            <th>Total Prospek</th>
            <th>Total Demo/Presentasi</th>
            <th>Total Closing Paid</th>
            <th>Total Pending</th>
            <th>Total Maintenance</th>
        </tr>
    </thead>
    <tbody>
        @php($no = 1)
        @foreach ($aktivitas as $value)
            <tr style="text-align: center;">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $value->salesHasMerchant->userSales->nama_user ?? '' }}</td>
                <td>{{ App\Utilities\Helpers::countMerchantBySalesManajer($value['manajer_id'], $value['sales_id']) ?? '' }}
                </td>
                @for ($i = 1; $i <= 5; $i++)
                    <td>{{ App\Utilities\Helpers::statusCount($value['sales_id'], $i) ?? '' }}</td>
                @endfor
            </tr>
        @endforeach
    </tbody>
</table>
