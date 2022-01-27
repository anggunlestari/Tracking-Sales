@extends('layout/admin')

@section('title', 'Edit Kategori')

@section('content')

    <div class="card card-custom">
        <div class="card-header">
            <h1 class="card-title">
                Edit Data Kategori
            </h1>
        </div>
        <!--begin::Form-->
        <form method="post" action="/admin/kategori/{{ $kategori->id }}">
            @method('patch')
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label>Nama Kategori</label>
                    <input class="form-control @error('nama_kategori') is-invalid @enderror" type="text" id="nama_kategori"
                        name="nama_kategori" value="{{ $kategori->nama_kategori }}" />
                    @error('nama_kategori')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea rows="4" class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi"
                        name="deskripsi">{{ $kategori->deskripsi }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
                <div class="forom-group mt-7">
                    <button type="submit" class="btn btn-primary mr-2">Edit</button>
                    <a href="/admin/kategori/table" class="btn btn-secondary">Batal</a>
                </div>
            </div>
        </form>
        <!--end::Form-->
    </div>

@endsection
