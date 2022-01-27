@extends('layout/manajer')

@section('judul', 'Profil Manajer')
@section('content')

    <div class="card card-custom">
        <div class="card-header">
            <h1 class="card-title">
                Profil Manajer
            </h1>
        </div>
        <!--begin::Form-->
        <form method="post" action="/manajer/{{ $user->id }}/ubahProfil" enctype="multipart/form-data">
            @method('patch')
            @csrf
            <div class="card-body">
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
                    <input class="form-control @error('nomor_telepon') is-invalid @enderror" type="text" id="nomor_telepon"
                        name="nomor_telepon" value="{{ $user->nomor_telepon }}" />
                    @error('nomor_telepon')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input class="form-control @error('email') is-invalid @enderror" type="email" id="email" name="email"
                        value="{{ $user->email }}" />
                    @error('email')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
                <div class=" form-group">
                    <label>Foto</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input @error('foto') is-invalid @enderror" id="customFile"
                            name="foto" />
                        <label class=" custom-file-label" for="customFile">{{ $user->foto }}</label>
                    </div>
                    @error('foto')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input class="form-control @error('password') is-invalid @enderror" type="password" id="password"
                        placeholder="masukan password baru" name="password" />
                    @error('password')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
                <div class="forom-group mt-7">
                    <button type="submit" class="btn btn-primary mr-2">Edit Profile</button>
                    <a href="/manajer/dashboard" class="btn btn-secondary">Batal</a>
                </div>
            </div>
        </form>
        <!--end::Form-->
    </div>
@endsection
