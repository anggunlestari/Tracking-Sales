@extends('layout/admin')

@section('title', 'Tambah Manajer')
@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <h1 class="card-title">
                Tambah Data Manajer
            </h1>
        </div>
        <!--begin::Form-->
        <form method="post" action="/admin/manajer/table" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <input class="form-control" type="hidden" id="role_id" name="role_id" value="{{ old('role_id') }}" />
                </div>
                <div class=" form-group">
                    <label>Area Lokasi *</label>
                    <select class="custom-select form-control @error('area_id') is-invalid @enderror" id="area_id"
                        name="area_id">
                        <option value="">pilih area lokasi</option>
                        @foreach ($area_lokasi as $value)
                            <option value="{{ $value->id }}">{{ $value->nama_area }}</option>
                        @endforeach
                    </select>
                    @error('area_id')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Nama Manajer *</label>
                    <input class="form-control @error('nama_user') is-invalid @enderror" type="text" id="nama_user"
                        name="nama_user" placeholder="masukan nama manajer" value="{{ old('nama_user') }}" />
                    @error('nama_user')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Nomor Telepon *</label>
                    <input class="form-control @error('nomor_telepon') is-invalid @enderror" type="text" id="nomor_telepon"
                        name="nomor_telepon" placeholder="masukan nomor telepon" value="{{ old('nomor_telepon') }}" />
                    @error('nomor_telepon')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Email *</label>
                    <input class="form-control @error('email') is-invalid @enderror" type="email" id="email" name="email"
                        placeholder="masukan email" value="{{ old('email') }}" />
                    @error('email')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Password *</label>
                    <input class="form-control @error('password') is-invalid @enderror" type="password" id="password"
                        name="password" placeholder="masukan password" value="{{ old('password') }}" />
                    @error('password')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
                <div class=" form-group">
                    <label>Foto</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input @error('foto') is-invalid @enderror" id="foto"
                            name="foto" value="{{ old('foto') }}" />
                        <label class=" custom-file-label" for="foto">pilih foto</label>
                    </div>
                    @error('foto')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
                <div class="forom-group mt-7">
                    <button type="submit" class="btn btn-primary mr-2">Simpan</button>
                    <button type="reset" class="btn btn-secondary">Batal</button>
                </div>
            </div>
        </form>
        <!--end::Form-->
    </div>
@endsection
