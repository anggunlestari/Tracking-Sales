@extends('layout/admin')

@section('title', 'Edit Area Lokasi')

@section('content')

    <div class="card card-custom">
        <div class="card-header">
            <h1 class="card-title">
                Edit Data Area Lokasi
            </h1>
        </div>
        <!--begin::Form-->
        <form method="post" action="/admin/areaLokasi/{{ $areaLokasi->id }}">
            @method('patch')
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label>Nama Area Lokasi</label>
                    <input class="form-control @if (session('nama_area')) is-invalid @endif" type="text" id="nama_area" name="nama_area"
                        value="{{ $areaLokasi->nama_area }}" />
                    @if (session('nama_area'))
                        <div class="invalid-feedback"> {{ session('nama_area') }} </div>
                    @endif
                </div>
                <div class="form-group">
                    <label>Provinsi</label>
                    <select class="custom-select form-control" id="province_id" name="province_id">
                        @foreach ($province as $value)
                            <option value="{{ $value->id }}"
                                {{ $value->id == $areaLokasi->province_id ? 'selected' : '' }}>
                                {{ $value->name }}</option>
                        @endforeach
                        @foreach ($provincedr as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Kabupaten</label>
                    <select class="custom-select form-control" id="city_id" name="city_id">
                        @foreach ($city as $value)
                            <option value="{{ $value->id }}"
                                {{ $value->id == $areaLokasi->city_id ? 'selected' : '' }}>
                                {{ $value->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Kecamatan</label>
                    <select class="custom-select form-control" id="district_id" name="district_id">
                        @foreach ($district as $value)
                            <option value="{{ $value->id }}"
                                {{ $value->id == $areaLokasi->district_id ? 'selected' : '' }}>
                                {{ $value->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="forom-group mt-7">
                    <button type="submit" class="btn btn-primary mr-2">Edit</button>
                    <a href="/admin/areaLokasi/table" class="btn btn-secondary">Batal</a>
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
                    url: '/admin/areaLokasi/edit/' + provinceID,
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
                    url: '/admin/areaLokasi/edit1/' + cityID,
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
