@extends('layout/admin')

@section('title', 'Edit Manajer')
@section('content')

    <div class="card card-custom">
        <div class="card-header">
            <h1 class="card-title">
                Edit Data Manajer
            </h1>
        </div>
        <!--begin::Form-->
        <div class="card-body">
            <form method="post" action="/admin/manajer/{{ $user->id }}">
                @method('patch')
                @csrf
                <div class="form-group">
                    <label>Area Lokasi</label>
                    <select class="custom-select form-control @if (session('area_id')) is-invalid @endif" id="area_id" name="area_id">
                        @foreach ($area_lokasi as $value)
                            <option value="{{ $value->id }}" {{ $value->id == $user->area_id ? 'selected' : '' }}>
                                {{ $value->nama_area ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    @if (session('area_id'))
                        <div class="invalid-feedback"> {{ session('area_id') }} </div>
                    @endif
                </div>
                <div class="form-group">
                    <label>Nama Manajer</label>
                    <input class="form-control @error('nama_user') is-invalid @enderror" type="text" id="nama_user"
                        name="nama_user" value="{{ $user->nama_user }}" />
                    @error('nama_user')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Nomor Telepon</label>
                    <input class="form-control   @if (session('nomor_telepon')) is-invalid @endif" type="text" id="nomor_telepon" name="nomor_telepon"
                        value="{{ $user->nomor_telepon }}" />
                    @if (session('nomor_telepon'))
                        <div class="invalid-feedback"> {{ session('nomor_telepon') }} </div>
                    @endif
                </div>
                <fieldset disabled>
                    <div class="form-group">
                        <label>Email</label>
                        <input class="form-control" type="text" id="email" name="email" value="{{ $user->email }}" />
                    </div>
                    <div class="form-group">
                        <label>Foto</label>
                        <input class="form-control" type="text" id="foto" name="foto" value="{{ $user->foto }}" />
                    </div>
                </fieldset>
                <div class="forom-group ">
                    <button type="submit" class="btn btn-primary mr-2">Edit</button>
                    <a href="/admin/manajer/table" class="btn btn-secondary">Batal</a>
                </div>
            </form>
            <form action="admin/manajer/{{ $user->id }}/edit" method="post">
                @method('patch')
                @csrf
                <div>
                    <button type="submit" id="resetPassword" class="btn btn-danger btn-sm my-7">Reset
                        Password</button>
                </div>
            </form>
        </div>
        <!--end::Form-->

    </div>

@endsection

@section('JS')
    <script>
        $(document).ready(function() {
            $("#resetPassword").on('click', function(e) {
                e.preventDefault();
                var form = $(this).parents('form');
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Anda yakin ingin mereset password user?',
                    icon: 'warning',
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
    </script>
@endsection
