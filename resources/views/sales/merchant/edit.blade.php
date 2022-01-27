@extends('layout/sales')

@section('title', 'Edit Merchant')
@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <h1 class="card-title">
                Edit Data Merchant/Partner
            </h1>
        </div>
        <!--begin::Form-->
        <form method="post" action="/sales/merchant/{{ $merchant['id'] }}" enctype="multipart/form-data">
            @method('patch')
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label>Nama Merchant/Partner</label>
                    <input class="form-control @error('nama_merchant') is-invalid @enderror" type="text" id="nama_merchant"
                        name="nama_merchant" value="{{ $merchant->nama_merchant }}" />
                    @error('nama_merchant')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
                <div class=" form-group">
                    <label>Kategori</label>
                    <select class="custom-select form-control @error('kategori_id') is-invalid @enderror" id="kategori_id"
                        name="kategori_id">
                        @foreach ($kategori as $value)
                            <option value="{{ $value->id }}"
                                {{ $value->id == $merchant->kategori_id ? 'selected' : '' }}>
                                {{ $value->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Nama Pemilik / PIC</label>
                    <input class="form-control @error('nama_pemilik') is-invalid @enderror" type="text" id="nama_pemilik"
                        name="nama_pemilik" value="{{ $merchant->nama_pemilik }}" />
                    @error('nama_pemilik')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
                <div class=" form-group">
                    <label>Nomor Telepon</label>
                    <input class="form-control @if (session('nomor_telepon')) is-invalid @endif" type="text" id="nomor_telepon" name="nomor_telepon"
                        value="{{ $merchant->nomor_telepon }}" />
                    @if (session('nomor_telepon'))
                        <div class="invalid-feedback"> {{ session('nomor_telepon') }} </div>
                    @endif
                </div>
                <div class=" form-group">
                    <label>Alamat</label>
                    <input class="form-control @error('alamat') is-invalid @enderror" type="text" id="alamat" name="alamat"
                        value="{{ $merchant->alamat }}" />
                    @error('alamat')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Provinsi</label>
                    <select class="custom-select form-control @error('province_id') is-invalid @enderror" id="province_id"
                        name="province_id">
                        @foreach ($province as $value)
                            <option value="{{ $value->id }}"
                                {{ $value->id == $merchant->province_id ? 'selected' : '' }}>
                                {{ $value->name }}</option>
                        @endforeach
                    </select>
                    @error('province_id')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Kabupaten</label>
                    <select class="custom-select form-control @error('city_id') is-invalid @enderror" id="city_id"
                        name="city_id">
                        @foreach ($city as $value)
                            <option value="{{ $value->id }}"
                                {{ $value->id == $merchant->city_id ? 'selected' : '' }}>
                                {{ $value->name }}</option>
                        @endforeach
                    </select>
                    @error('city_id')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Kecamatan</label>
                    <select class="custom-select form-control @error('district_id') is-invalid @enderror" id="district_id"
                        name="district_id">
                        @foreach ($district as $value)
                            <option value="{{ $value->id }}"
                                {{ $value->id == $merchant->district_id ? 'selected' : '' }}>
                                {{ $value->name }}</option>
                        @endforeach
                    </select>
                    @error('district_id')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
                <div>
                    <fieldset disabled>
                        <div class="row mb-5">
                            <div class="col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label>Latitude</label>
                                    <input class="form-control @error('latitude') is-invalid @enderror" type="text"
                                        id="latitude" name="latitude" value="{{ $merchant->latitude }}" />
                                    @error('latitude')
                                        <div class="invalid-feedback"> {{ $message }} </div>
                                    @enderror
                                </div>
                                <div class=" form-group">
                                    <label>Longitude</label>
                                    <input class="form-control @error('longitude') is-invalid @enderror" type="text"
                                        id="longitude" name="longitude" value="{{ $merchant->longitude }}" />
                                    @error('longitude')
                                        <div class="invalid-feedback"> {{ $message }} </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="form-group">
                    <label>Foto</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input @error('foto') is-invalid @enderror" id="customFile"
                            name="foto" />
                        <label class="custom-file-label" for="customFile"> {{ $merchant->foto }} </label>
                        @error('foto')
                            <div class="invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div>
                </div>
                <div class="forom-group mt-7">
                    <button type="submit" class="btn btn-primary mr-2">Edit</button>
                    <a href="/sales/merchant/table" class="btn btn-secondary">Batal</a>
                </div>
            </div>
        </form>
        <!--end::Form-->
    </div>
@endsection

@section('JS')

    {{-- Untuk Mendefinisikan jQuery --}}
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>

    {{-- Script Dependent Dropdown LARAVOLT --}}
    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('select[name="province_id"]').on('change', function() {
                var provinceID = jQuery(this).val();
                if (provinceID) {
                    jQuery.ajax({
                        url: '/sales/merchant/edit/' + provinceID,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            if (data) {
                                console.log(data);
                                jQuery('select[name="city_id"]').empty();
                                jQuery.each(data, function(key, value) {
                                    $('select[name="city_id"]').append(
                                        '<option value="' +
                                        key + '">' + value + '</option>');
                                });
                            } else {
                                $('select[name="city_id"]').empty();
                            }
                        }
                    });
                } else {
                    $('select[name="city_id"]').empty();
                    $('select[name="district_id"]').empty();
                }
            });
            //
            jQuery('select[name="city_id"]').on('change', function() {
                var cityID = jQuery(this).val();
                if (cityID) {
                    jQuery.ajax({
                        url: '/sales/merchant/edit1/' + cityID,
                        type: "GET",
                        dataType: "json",
                        success: function(res) {
                            if (res) {
                                console.log(res);
                                jQuery('select[name="district_id"]').empty();
                                jQuery.each(res, function(key, value) {
                                    $('select[name="district_id"]').append(
                                        '<option value="' +
                                        key + '">' + value + '</option>');
                                });
                            } else {
                                $('select[name="district_id"]').empty();
                            }
                        }
                    });
                } else {
                    $('select[name="district_id"]').empty();
                }
            });
        });
    </script>

@endsection
