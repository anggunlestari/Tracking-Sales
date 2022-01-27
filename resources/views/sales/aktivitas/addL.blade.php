@extends('layout/sales')

@section('title', 'Aktivitas')
@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title">Aktivitas Kunjungan Merchant/Partner
            </h3>
        </div>

        <!--begin::Form-->
        <form method="post" action="/sales/aktivitas/tableL" enctype="multipart/form-data">
            @method('post')
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <input class="form-control" type="hidden" id="manajer_id" name="manajer_id" />
                </div>
                <div class="form-group">
                    <input class="form-control" type="hidden" id="sales_id" name="sales_id" />
                </div>
                <div class=" form-group">
                    <label>Nama Merchant/Partner *</label>
                    <select class="custom-select form-control @error('merchant_id') is-invalid @enderror" id="merchant_id"
                        name="merchant_id">
                        <option value="">pilih merchant/partner</option>
                        @foreach ($merchant as $value)
                            <option value="{{ $value->id }}">{{ $value->nama_merchant }}</option>
                        @endforeach
                    </select>
                    @error('merchant_id')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
                <div class="form-group mt-7">
                    <label>Tanggal</label>
                    <input class="form-control @error('date') is-invalid @enderror" type="date" id="date" name="date"
                        value="{{ old('date') }}" />
                    {{-- value="{{ \Carbon\Carbon::createFromDate($db->year, $db->month, $db->day)->format('Y-m-d') }}" --}}
                    @error('date')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Status *</label>
                    <select class="custom-select form-control @error('status_id') is-invalid @enderror" id="status_id"
                        name="status_id">
                        <option value="">pilih status</option>
                        @foreach ($status as $value)
                            <option value="{{ $value->id }}">{{ $value->nama_status }}</option>
                        @endforeach
                    </select>
                    @error('status_id')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
                <div class="form-group mb-1">
                    <label for="exampleTextarea">Keterangan</label>
                    <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan"
                        name="keterangan" value="{{ old('keterangan') }}" rows="3"></textarea>
                    @error('keterangan')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
                <div class="form-group mt-7">
                    <label>Nominal</label>
                    <input class="form-control" type="text" id="nominal" name="nominal" placeholder="masukan jumlah nominal"
                        value="{{ old('nominal') }}" />
                </div>
                <div class="form-group">
                    <label>Foto</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="customFile" name="foto" />
                        <label class="custom-file-label" for="customFile">pilih foto</label>
                    </div>
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
