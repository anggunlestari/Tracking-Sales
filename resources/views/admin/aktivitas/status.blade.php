@extends('layout/admin')

@section('title', 'Tabel Aktivitas')

@section('CSS')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">Tabel Aktivitas
                    <span class="d-block text-muted pt-2 font-size-sm">Tabel Aktivitas</span>
                </h3>
            </div>
        </div>

        <div class="card-body">
            <div class="mb-7">
                <!--begin: Datatable-->
                <table class="table table-bordered table-head-custom" id="data_source">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Manajer</th>
                            <th>Nama Sales</th>
                            <th>Nama Merchant/Partner</th>
                            <th>Status</th>
                            <th>Nominal</th>
                            {{-- <th>Aksi</th> --}}
                        </tr>
                    </thead>
                </table>
                <!--end: Datatable-->
            </div>
        </div>
    </div>
@endsection


@section('JS')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>

    <script>
        $(document).ready(function() {
            $("#data_source").on('click', '.hapus', function(e) {
                e.preventDefault();
                var form = $(this).parents('form');
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Anda yakin ingin menghapus data ini?',
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.value) {
                        form.submit();
                    }
                })
            });
        });

        var table = $('#data_source').DataTable({
            responsive: true,
            searchDelay: 500,
            processing: true,
            serverSide: true,
            ordering: true,
            ajax: '{{ route('dashboard.data', ['status_id' => $status_id]) }}',
            columns: [{
                    data: 'tanggal',
                    name: 'tanggal'
                },
                {
                    data: 'aktivitas.merchant.manajer_has_merchant.user_manajer.nama_user',
                    name: 'aktivitas.merchant.manajer_has_merchant.user_manajer.nama_user',
                },
                {
                    data: 'aktivitas.merchant.sales_has_merchant.user_sales.nama_user',
                    name: 'aktivitas.merchant.sales_has_merchant.user_sales.nama_user',
                },
                {
                    data: 'aktivitas.merchant.nama_merchant',
                    name: 'aktivitas.merchant.nama_merchant'
                },
                {
                    data: 'status',
                    name: 'status.nama_status'
                },
                {
                    data: 'nominal',
                    name: 'nominal'
                },
                // {
                //     data: 'actions',
                //     name: 'actions',
                // }
            ],
        });
    </script>
@endsection
