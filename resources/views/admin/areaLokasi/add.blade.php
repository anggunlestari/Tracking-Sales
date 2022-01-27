@extends('layout/admin')

@section('title', 'Tambah Area Lokasi')

@section('content')

    <div class="card card-custom">
        <div class="card-header">
            <h1 class="card-title">
                Tambah Data Area Lokasi
            </h1>
        </div>
        <!--begin::Form-->
        <form method="post" action="/admin/areaLokasi/table">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label>Nama Area Lokasi *</label>
                    <input class="form-control @error('nama_area') is-invalid @enderror" type="text" id="nama_area"
                        name="nama_area" placeholder="masukan area lokasi" value="{{ old('nama_area') }}" />
                    @error('nama_area')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
                <div class=" form-group">
                    <label>Provinsi *</label>
                    <select class="custom-select form-control dynamic @error('province_id') is-invalid @enderror"
                        id="province_id" name="province_id" value="{{ old('province_id') }}">
                        <option value="">pilih provinsi</option>
                        @foreach ($provincedr as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                    @error('province_id')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
                <div class=" form-group">
                    <label>Kabupaten *</label>
                    <select class="custom-select form-control @error('city_id') is-invalid @enderror" id="city_id"
                        name="city_id" value="{{ old('city_id') }}">
                        <option value="">pilih kabupaten</option>
                    </select>
                    @error('city_id')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
                <div class=" form-group">
                    <label>Kecamatan *</label>
                    <select class="custom-select form-control @error('district_id') is-invalid @enderror" id="district_id"
                        name="district_id" value="{{ old('district_id') }}">
                        <option value="">pilih kecamatan</option>
                    </select>
                    @error('district_id')
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


{{-- Untuk Mendefinisikan jQuery --}}
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>

{{-- Script Dependent Dropdown --}}
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('select[name="province_id"]').on('change', function() {
            var provinceID = jQuery(this).val();
            if (provinceID) {
                jQuery.ajax({
                    url: '/admin/areaLokasi/add/' + provinceID,
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
                    url: '/admin/areaLokasi/add1/' + cityID,
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
