@extends('layout/sales')

@section('title', 'Edit Aktivitas Sales')

@section('content')

    <div class="card card-custom mb-10">
        <div class="card-header">
            <h1 class="card-title">
                Aktivitas Sales Merchant/Partner {{ $history[0]->aktivitas->merchant->nama_merchant }}
            </h1>
        </div>

        <div class="card-body">
            <table class="datatable datatable-bordered datatable-head-custom" id="kt_datatable">
                <thead>
                    <tr>
                        <th title="Field #1">Tanggal</th>
                        <th title="Field #4">status</th>
                        <th title="Field #5">Keterangan</th>
                        <th title="Field #6">jumlah Nominal</th>
                        <th title="Field #7">Foto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($history as $value)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($value->tanggal)->format('d-m-Y') ?? '' }}</td>
                            <td>{{ $value->status->nama_status ?? '' }}</td>
                            <td>{{ $value->keterangan ?? '' }}</td>
                            <td>{{ \App\Utilities\Helpers::formatCurrency($value->nominal, 'Rp' ?? '') }}
                            </td>
                            <td><img src="{{ asset('storage/aktivitas/' . $value->foto) ?? '' }}" alt="" width="40"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    @php($no = 0)
    @foreach ($history as $value)
        <div class="card card-custom mb-7">
            <div class="card-header">
                <h1 class="card-title">
                    Edit Kunjungan {{ $loop->iteration }}
                </h1>
            </div>
            <div class="card-body">
                <form method="post" action="/sales/aktivitas/edit" enctype="multipart/form-data">
                    @method('patch')
                    @csrf
                    {{-- name="x[]" biar bisa nampung lebih dari 1 value --}}
                    <div class="form-group">
                        <input class="form-control @error('id') is-invalid @enderror" type="hidden" name="id[]"
                            value="{{ $history[$no++]->id }}">
                        @error('id')
                            <div class="invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div>
                    {{-- <fieldset disabled>
                        <div class="form-group">
                            <label>Nama Merchant/Partner</label>
                            <input class="form-control" type="text" id="nama_merchant" name="nama_merchant"
                                value="{{ $history[0]->aktivitas->merchant->nama_merchant }}" />
                        </div>
                    </fieldset> --}}
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input class="form-control @error('date') is-invalid @enderror" type="date" id="date" name="date[]"
                            value="{{ $value->tanggal }}" />
                        @error('date')
                            <div class="invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select class="custom-select form-control @error('status_id') is invalid @enderror" id="status_id"
                            name="status_id[]">
                            @foreach ($status as $stat)
                                <option value="{{ $stat->id }}"
                                    {{ $stat->id == $value->status_id ? 'selected' : '' }}>
                                    {{ $stat->nama_status }}
                                </option>
                            @endforeach
                        </select>
                        @error('status_id')
                            <div class="invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div>
                    <div class="form-group mb-1">
                        <label for="exampleTextarea">Keterangan</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan"
                            name="keterangan[]" rows=" 3">{{ $value->keterangan }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div>
                    <div class="form-group mt-7">
                        <label>Nominal</label>
                        <input class="form-control @error('nominal') is-invalid @enderror" type="text" id="nominal"
                            name="nominal[]" value="{{ $value->nominal }}" />
                        @error('nominal')
                            <div class="invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Foto</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input @error('foto') is-invalid @enderror" id="customFile"
                                name="foto[]" value="{{ $value->foto }}" />
                            <label class="custom-file-label" for="customFile"> {{ $value->foto }}
                            </label>
                        </div>
                        @error('foto')
                            <div class="invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div>
                    <br>
            </div>
        </div>
    @endforeach
    <div class="container">
        <div class="row">
            <div class="col-lg-10 col-md-12 ml-30 text-center">
                <div class="forom-group mb-5">
                    <button type="submit" class="btn btn-primary mr-2">Edit</button>
                    <a href="/sales/aktivitas/tableL" class="btn btn-secondary">Batal</a>
                </div>
                </form>
            </div>
        </div>
    </div>

@endsection
