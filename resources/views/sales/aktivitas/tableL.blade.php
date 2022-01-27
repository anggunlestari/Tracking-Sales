@extends('layout/sales')

@section('title', 'Laporan Aktivitas')

@section('CSS')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    <!--begin::Card-->
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">Tabel Laporan Aktivitas
                    <span class="d-block text-muted pt-2 font-size-sm">Tabel Laporan Aktivitas</span>
                </h3>
            </div>
            <div class="card-toolbar">
                <!--begin::AddData-->
                <div class="my-3 ml-5">
                    <a href="/sales/aktivitas/addL" class="d-inline">
                        <button class="btn btn-primary">
                            <i class="far fa-plus-square"></i>Tambah Aktivitas Baru</button>
                    </a>
                </div>
                <!--end::AddData-->
            </div>
        </div>
        <div class="card-body">
            <!--begin: Datatable-->
            <table class="table table-bordered table-head-custom" id="data_source">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Merchant/Partner</th>
                        <th>Status</th>
                        <th>Nominal</th>
                        <th>Aktivitas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
            <!--end: Datatable-->
        </div>
    </div>
    <!--end::Card-->
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
            ajax: '{{ route('aktivitas.dataS') }}',
            columns: [
                // {
                //     data: 'updated_at',
                //     name: 'updated_at',
                // },
                {
                    data: 'tanggal',
                    name: 'tanggal',
                },
                {
                    data: 'aktivitas.merchant.nama_merchant',
                    name: 'aktivitas.merchant.nama_merchant',
                },
                {
                    data: 'status',
                    name: 'status.nama_status',
                },
                {
                    data: 'nominal',
                    name: 'nominal',
                },
                {
                    data: 'aktivity',
                    name: 'aktivity',
                },
                {
                    data: 'actions',
                    name: 'actions',
                }
            ],
        });
    </script>
@endsection
