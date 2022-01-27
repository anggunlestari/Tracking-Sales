<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Aktivitas;
use App\Models\LogAktivitas;
use App\Models\SalesHasMerchant;
use App\Models\Merchant;
use App\Models\Status;
use App\Models\User;
use App\Utilities\Helpers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use DataTables;

class AAktivitasController extends Controller
{
    public function index()
    {
        return view('admin.aktivitas.table');
    }

    public function indexData(Request $request)
    {
        //untuk set display filter by date
        if ($request->has('start_date') && $request->has('end_date')) {
            $start_date = Carbon::parse($request['start_date'])->toDateString();
            $end_date = Carbon::parse($request['end_date'])->toDateString();
        } else {
            $start_date = now()->firstOfMonth()->toDateString();
            $end_date = now()->lastOfMonth()->toDateString();
        }

        $aktivitas = LogAktivitas::with(['status'])
            ->with(['aktivitas', 'aktivitas.merchant'])
            ->with(['aktivitas.merchant.salesHasMerchant', 'aktivitas.merchant.salesHasMerchant.userSales'])
            ->with(['aktivitas.merchant.manajerHasMerchant', 'aktivitas.merchant.manajerHasMerchant.userManajer'])
            ->whereDate('tanggal', '>=', $start_date)
            ->whereDate('tanggal', '<=', $end_date)
            ->latest()->get();

        $aktivitas = $aktivitas->groupBy('aktivitas_id')->map(function ($data) {
            return $data->first();
        })->values();

        // untuk bagian yg perlu format2 (helpers, button, tanggal, nominal, status)
        return DataTables::of($aktivitas)
            ->editColumn('tanggal', function ($data) {
                return  \Carbon\Carbon::parse($data->tanggal)->format('d-m-Y');
            })
            ->editColumn('nominal', function ($data) {
                return Helpers::formatCurrency($data->nominal, 'Rp');
            })
            ->editColumn('status', function ($data) {
                return Helpers::statusColour($data->status->nama_status ?? '');
            })
            ->addColumn('actions', function ($data) {
                return  '
                <a href="/admin/aktivitas/' . $data->aktivitas_id . '/edit" class="d-inline">
                    <button class="btn btn-warning btn-sm"> <i class="far fa-edit text-white"></i></button>
                </a>
                <form action="/admin/aktivitas/' . $data->aktivitas_id . '" method="post" class="d-inline">
                    <input type="hidden" name="_method" value="delete">
                    ' . @csrf_field() . '
                    <button type="submit" class="hapus btn btn-danger btn-sm"><i class="far fa-trash-alt text-white"></i></button>
                </form>';
            })
            ->rawColumns(['status', 'actions'])
            ->toJson();
    }

    public function exportDateExcel($tglawal, $tglakhir)
    {
        // dd("Tanggal Awal : " . $tglawal, "Tanggal Akhir : " . $tglakhir);
        $cetak = LogAktivitas::with(['status'])
            ->with(['aktivitas', 'aktivitas.merchant'])
            ->with(['aktivitas.merchant.salesHasMerchant', 'aktivitas.merchant.salesHasMerchant.userSales'])
            ->with(['aktivitas.merchant.manajerHasMerchant', 'aktivitas.merchant.manajerHasMerchant.userManajer'])
            ->whereBetween('tanggal', [$tglawal, $tglakhir])
            ->latest()->get();

        $cetak = $cetak->groupBy('aktivitas_id')->map(function ($data) {
            return $data->first();
        })->values();

        return view('admin.aktivitas.exportDateExcel', compact('cetak', 'tglawal', 'tglakhir'));
    }

    public function exportDatePdf($tglawal, $tglakhir)
    {
        $cetak = LogAktivitas::with(['status'])
            ->with(['aktivitas', 'aktivitas.merchant'])
            ->with(['aktivitas.merchant.salesHasMerchant', 'aktivitas.merchant.salesHasMerchant.userSales'])
            ->with(['aktivitas.merchant.manajerHasMerchant', 'aktivitas.merchant.manajerHasMerchant.userManajer'])
            ->whereBetween('tanggal', [$tglawal, $tglakhir])
            ->latest()->get();

        $cetak = $cetak->groupBy('aktivitas_id')->map(function ($data) {
            return $data->first();
        })->values();

        return view('admin.aktivitas.exportDatePdf', compact('cetak', 'tglawal', 'tglakhir'));
    }

    public function edit($aktivitas)
    {
        $status = Status::get();
        $history = LogAktivitas::with(['aktivitas', 'aktivitas.merchant', 'aktivitas.merchant.manajerHasMerchant', 'aktivitas.merchant.manajerHasMerchant.userManajer', 'aktivitas.merchant.salesHasMerchant', 'aktivitas.merchant.salesHasMerchant.userSales'])
            ->with(['status'])
            ->where('aktivitas_id', $aktivitas)->get();

        return view('admin.aktivitas.edit', compact('history', 'status'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'status_id' => 'required',
            'keterangan' => 'required',
            'nominal' => 'required',
            // 'foto' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            'date' => 'required',
        ]);

        // dd($request->hasFile('foto.2'));

        //foto. = untuk menapung lebih dari 1 nilai (bentuk array)
        //'foto.' . $no = foto.2 = foto[2] > foto di array ke-2
        $no = 0;
        // dd(last($request->input('id')));
        foreach ($request->input('id') as $value) {
            if ($request->hasFile('foto.' . $no)) {
                $image = $request->file('foto.' . $no);
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                Storage::disk('public')->put('aktivitas/' . $imageName, file_get_contents($image));

                $request['foto'] = $imageName ?? $request->foto;

                LogAktivitas::where('id', $value)
                    ->update([
                        'foto' => $request['foto'] = $imageName ?? $request->foto,
                    ]);
            }

            //PERUBAHAN
            if ($value == last($request->input('id'))) {
                $logAktivitas = LogAktivitas::where('id', $value)->first();

                Aktivitas::where('id', $logAktivitas->aktivitas_id)->where('status_id', $logAktivitas->status_id)
                    ->update([
                        'status_id' =>  $request->input('status_id.' . $no),
                    ]);
            }

            LogAktivitas::where('id', $value)
                ->update([
                    'status_id' => $request->input('status_id.' . $no),
                    'keterangan' => $request->input('keterangan.' . $no),
                    'nominal' => $request->input('nominal.' . $no),
                    'tanggal' => $request->input('date.' . $no),
                ]);

            $no++;
        }

        Alert::toast('Data Aktivitas Sales Berhasil Di Update', 'success');

        //stay ke halaman ini
        return back();
    }

    public function destroy(Aktivitas $aktivitas)
    {
        //delete table logAktivitas
        $logAktivitas = LogAktivitas::where('aktivitas_id', $aktivitas->id)->get(['id']);
        foreach ($logAktivitas as $aktiv) {
            LogAktivitas::destroy($aktiv->toArray());
        }

        //delete table aktivitas
        Aktivitas::destroy($aktivitas->id);

        Alert::toast('Data Aktivitas Sales Berhasil Di Hapus', 'success');

        return redirect('/admin/aktivitas/table');
    }
}
