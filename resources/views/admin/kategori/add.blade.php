@extends('layout/admin')

@section('title', 'Tambah Kategori')

@section('content')

    <div class="card card-custom">
        <div class="card-header">
            <h1 class="card-title">
                Tambah Data Kategori
            </h1>
        </div>
        <!--begin::Form-->
        <form method="post" action="/admin/kategori/table">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label>Nama Kategori *</label>
                    <input class="form-control @error('nama_kategori') is-invalid @enderror" type="text" id="nama_kategori"
                        name="nama_kategori" placeholder="masukan kategori" value="{{ old('nama_kategori') }}" />
                    @error('nama_kategori')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Deskripsi *</label>
                    <textarea rows="4" class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi"
                        name="deskripsi" placeholder="masukan deskripsi" value="{{ old('deskripsi') }}"></textarea>
                    @error('deskripsi')
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
