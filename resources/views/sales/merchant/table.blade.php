@extends('layout/sales')

@section('title', 'Tabel Merchant')

@section('CSS')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">Tabel Merchant/Partner
                    <span class="d-block text-muted pt-2 font-size-sm">Tabel Merchant/Partner</span>
                </h3>
            </div>
            <div class="card-toolbar">
                <!--begin::AddData-->
                <div class="my-3 ml-5">
                    <a href="/sales/merchant/add" class="d-inline">
                        <button class="btn btn-primary">
                            <i class="far fa-plus-square"></i>Tambah Merchant/Partner</button>
                    </a>
                </div>
                <!--end::AddData-->
            </div>
        </div>

        <div class="card-body">
            <div class="mb-7">
                <!--begin: Datatable-->
                <table class="table table-bordered table-head-custom" id="data_source">
                    <thead>
                        <tr>
                            <th>Nama Merchant/Partner</th>
                            <th>Nomor Telepon</th>
                            <th>Nama Pemilik</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
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
            ajax: '{{ route('merchant.dataS') }}',
            columns: [{
                    data: 'nama_merchant',
                    name: 'nama_merchant',
                },
                {
                    data: 'nomor_telepon',
                    name: 'nomor_telepon',
                },
                {
                    data: 'nama_pemilik',
                    name: 'nama_pemilik',
                },
                {
                    data: 'alamat',
                    name: 'alamat',
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false
                }
            ],
        });
    </script>
@endsection
