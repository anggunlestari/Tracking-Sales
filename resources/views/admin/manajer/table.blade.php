@extends('layout/admin')

@section('title', 'Tabel Manajer')

@section('CSS')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">Tabel Manajer
                    <span class="d-block text-muted pt-2 font-size-sm">Tabel Manajer</span>
                </h3>
            </div>
            <div class="card-toolbar">
                <!--begin::TrashData-->
                <div class="align-items-center ml-3">
                    <a href="/admin/manajer/trash" class="d-inline">
                        <button class="btn btn-danger">
                            <i class="far fa-trash-alt text-white"></i>Backup Manajer</button>
                    </a>
                </div>
                <!--end::TrashData-->

                <!--begin::AddData-->
                <div class="ml-3">
                    <a href="/admin/manajer/add" class="d-inline">
                        <button class="btn btn-primary">
                            <i class="far fa-plus-square"></i>Tambah Manajer</button>
                    </a>
                </div>
                <!--end::AddData-->

                <!--begin::Dropdown-->
                <div class="dropdown dropdown-inline mr-2 ml-3">
                    <button type="button" class="btn btn-light-primary font-weight-bolder dropdown-toggle"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="svg-icon svg-icon-md">
                            <!--begin::Svg Icon | path:assets/media/svg/icons/Design/PenAndRuller.svg-->
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24" />
                                    <path
                                        d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z"
                                        fill="#000000" opacity="0.3" />
                                    <path
                                        d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z"
                                        fill="#000000" />
                                </g>
                            </svg>
                            <!--end::Svg Icon-->
                        </span>Export
                    </button>
                    <!--begin::Dropdown Menu-->
                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                        <!--begin::Navigation-->
                        <ul class="navi flex-column navi-hover py-2">
                            <li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Choose
                                an option:</li>
                            <li class="navi-item">
                                <a href="{{ route('exportExcelManajer') }}" class="navi-link">
                                    <span class="navi-icon">
                                        <i class="la la-file-excel-o"></i>
                                    </span>
                                    <span class="navi-text">Excel</span>
                                </a>
                            </li>
                            <li class="navi-item">
                                <a href="{{ route('exportPdfManajer') }}" class="navi-link">
                                    <span class="navi-icon">
                                        <i class="la la-file-pdf-o"></i>
                                    </span>
                                    <span class="navi-text">PDF</span>
                                </a>
                            </li>
                        </ul>
                        <!--end::Navigation-->
                    </div>
                    <!--end::Dropdown Menu-->
                </div>
                <!--end::Dropdown-->
            </div>
        </div>

        <div class="card-body">
            <div class="mb-7">
                <!--begin: Datatable-->
                <table class="table table-bordered table-head-custom" id="data_source">
                    <thead>
                        <tr>
                            <th>Area Lokasi</th>
                            <th>Nama Manajer</th>
                            <th>Nomor Telepon</th>
                            <th>Email</th>
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
            ajax: '{{ route('manajer.data') }}',
            columns: [{
                    data: 'area_lokasi.nama_area',
                    name: 'area_lokasi.nama_area',
                },
                {
                    data: 'nama_user',
                    name: 'nama_user',
                },
                {
                    data: 'nomor_telepon',
                    name: 'nomor_telepon',
                },
                {
                    data: 'email',
                    name: 'email',
                },
                {
                    data: 'actions',
                    name: 'actions',
                }
            ],
        });
    </script>
@endsection
