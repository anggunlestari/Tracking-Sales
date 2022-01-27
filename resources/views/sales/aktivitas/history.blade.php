@extends('layout/sales')

@section('title', 'History Kunjungan')

@section('content')

    <div class="card card-custom mb-10">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h1 class="card-label">History Kunjungan {{ $history[0]->aktivitas->merchant->nama_merchant }}
                </h1>
            </div>
            <div class="card-toolbar">
            </div>
        </div>

        <div class="card-body">
            <table class="datatable datatable-bordered datatable-head-custom" id="kt_datatable">
                <thead>
                    <tr>
                        <th title="Field #1">Tanggal</th>
                        <th title="Field #2">status</th>
                        <th title="Field #3">Keterangan</th>
                        <th title="Field #4">Nominal</th>
                        <th title="Field #5">Foto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($history as $value)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($value->tanggal)->format('d-m-Y') ?? '' }}
                            </td>
                            <td>{{ $value->status->nama_status ?? '' }}</td>
                            <td>{{ $value->keterangan ?? '' }}</td>
                            <td>{{ \App\Utilities\Helpers::formatCurrency($value->nominal, 'Rp') ?? '' }}
                            </td>
                            <td><img src="{{ asset('storage/aktivitas/' . $value->foto ?? '') }}" alt="" width="40">
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>

    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title">
                Tambah Aktivitas Kunjungan {{ $history[0]->aktivitas->merchant->nama_merchant }}
            </h3>
        </div>

        <form method="post" action="/sales/aktivitas/history" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <input class="form-control form-control @error('aktivitas_id') is-invalid @enderror" type="hidden"
                        id="aktivitas_id" name="aktivitas_id" value="{{ $history[0]->aktivitas_id }}" />
                    @error('aktivitas_id')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
                <div class="form-group mt-7">
                    <label>Tanggal</label>
                    <input class="form-control @error('date') is-invalid @enderror" type="date" id="date" name="date"
                        value="{{ old('date') }}" />
                    @error('date')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Status</label>
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
                    <textarea class="form-control @error('katerangan') is-invalid @enderror" id="keterangan"
                        name="keterangan" value="{{ old('keterangan') }}" rows="3"></textarea>
                    @error('katerangan')
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
