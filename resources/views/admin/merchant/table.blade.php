@extends('layout/admin')

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
                <!--begin::Dropdown-->
                <!--begin::AddData-->
                <div class="my-3 ml-5">
                    <a href="/admin/merchant/add" class="d-inline">
                        <button class="btn btn-primary">
                            <i class="far fa-plus-square"></i>Tambah Merchant/Partner</button>
                    </a>
                </div>
                <!--end::AddData-->

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
                                <a href="{{ route('exportExcelMerchant') }}" class="navi-link">
                                    <span class="navi-icon">
                                        <i class="la la-file-excel-o"></i>
                                    </span>
                                    <span class="navi-text">Excel</span>
                                </a>
                            </li>
                            <li class="navi-item">
                                <a href="{{ route('exportPdfMerchant') }}" class="navi-link">
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
                            <th>Nama Manajer</th>
                            <th>Nama Sales</th>
                            <th>Nama Merchant/Partner</th>
                            <th>Aktivitas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    {{-- <tbody>
                        @php($no = 1)
                            @foreach ($merchant as $value)
                                <tr>
                                    <td>{{ $loop->iteration ?? '' }}</td>
                                    <td>{{ $value->manajerHasMerchant->userManajer->nama_user ?? '' }}</td>
                                    <td>{{ $value->salesHasMerchant->userSales->nama_user ?? '' }}
                                    </td>
                                    <td>{{ $value->nama_merchant ?? '' }}</td>
                                    <td><a href="{{ $value->id ?? '' != '' ? 'admin/merchant/' . $value->id . '/history' : 'admin/merchant/table' }}"
                                            class="btn btn-light-success btn-sm font-weight-bolder">History</a>
                                    </td>
                                    <td class="container text-left">
                                        <button data-toggle="modal" data-target="#view-modal{{ $value->id }}"
                                            class="btn btn-primary btn-sm">
                                            <i class=" far fa-eye text-white text-center"></i>
                                        </button>
                                        <a href="/admin/merchant/{{ $value->id }}/edit" class="d-inline">
                                            <button class="btn btn-warning btn-sm">
                                                <i class="far fa-edit text-white"></i></button>
                                        </a>
                                        <form action="/admin/merchant/{{ $value->id }}" method="post" class="d-inline">
                                            @method('delete')
                                            @csrf
                                            <button type="submit" class="hapus btn btn-danger btn-sm"><i
                                                    class="far fa-trash-alt text-white"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                <!--begin: Modal Detail-->
                                <div class="modal fade" id="view-modal{{ $value->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLongTitle" aria-hidden="true" style="display:none;">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Detail Merchant</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <fieldset disabled>
                                                    <div class="form-group">
                                                        <label>Nama Manajer</label>
                                                        <input class="form-control" type="text" id="nama_user" name="nama_user"
                                                            value="{{ $value->manajerHasMerchant->userManajer->nama_user ?? '' }}" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Nama Sales</label>
                                                        <input class="form-control" type="text" id="nama_user" name="nama_user"
                                                            value="{{ $value->salesHasMerchant->userSales->nama_user ?? '' }}" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Nama Merchant</label>
                                                        <input class="form-control" type="text" id="nama_merchant"
                                                            name="nama_merchant" value="{{ $value->nama_merchant ?? '' }}" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Kategori</label>
                                                        <input class="form-control" type="text" id="kategori_id"
                                                            name="kategori_id"
                                                            value="{{ $value->kategori->nama_kategori ?? '' }}" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Nama Pemilik</label>
                                                        <input class="form-control" type="text" id="nama_pemilik"
                                                            name="nama_pemilik" value="{{ $value->nama_pemilik ?? '' }}" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Nomor Telepon</label>
                                                        <input class="form-control" type="text" id="nomor_telepon"
                                                            name="nomor_telepon" value="{{ $value->nomor_telepon ?? '' }}" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Alamat</label>
                                                        <input class="form-control" type="text" id="alamat" name="alamat"
                                                            value="{{ $value->alamat ?? '' }}" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Provinsi</label>
                                                        <input class="form-control" type="text" id="province_id"
                                                            name="province_id" value="{{ $value->province->name ?? '' }}" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Kabupaten</label>
                                                        <input class="form-control" type="text" id="city_id" name="city_id"
                                                            value="{{ $value->city->name ?? '' }}" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Kecamatan</label>
                                                        <input class="form-control" type="text" id="district_id"
                                                            name="district_id" value="{{ $value->district->name ?? '' }}" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Latitude</label>
                                                        <input class="form-control" type="text" id="latitude" name="latitude"
                                                            value="{{ $value->latitude ?? '' }}" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Longitude</label>
                                                        <input class="form-control" type="text" id="longitude" name="longitude"
                                                            value="{{ $value->longitude ?? '' }}" />
                                                    </div>
                                                    <div class=" form-group">
                                                        <label>Foto</label>
                                                        <div class="custom-file">
                                                            <input type="file" id="foto" name="foto" />
                                                            <label class="custom-file-label"
                                                                for="foto">{{ $value->foto ?? '' }}</label>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end: Modal Detail-->
                            @endforeach
                        </tbody> --}}
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
            ajax: '{{ route('merchant.data') }}',
            columns: [{
                    data: 'manajer_has_merchant.user_manajer.nama_user',
                    name: 'manajer_has_merchant.user_manajer.nama_user',
                },
                {
                    data: 'sales_has_merchant.user_sales.nama_user',
                    name: 'sales_has_merchant.user_sales.nama_user',
                },
                {
                    data: 'nama_merchant',
                    name: 'nama_merchant',
                },
                {
                    //nama tabelnya bebas (data)
                    data: 'aktivitas',
                    name: 'tombol',
                    orderable: false
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
