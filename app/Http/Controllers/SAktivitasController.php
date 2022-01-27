<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\Aktivitas;
use App\Models\Merchant;
use App\Models\SalesHasMerchant;
use App\Models\Status;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use App\Models\ManajerHasSales;
use Illuminate\Support\Facades\DB;
use DataTables;
use App\Utilities\Helpers;

class SAktivitasController extends Controller
{
    public function index()
    {
        return view('sales.aktivitas.tableL');
    }

    public function indexData()
    {
        $aktivitas = LogAktivitas::with(['status'])
            ->with(['aktivitas', 'aktivitas.merchant'])
            ->with(['aktivitas.merchant.salesHasMerchant', 'aktivitas.merchant.salesHasMerchant.userSales'])
            ->with(['aktivitas.merchant.manajerHasMerchant', 'aktivitas.merchant.manajerHasMerchant.userManajer'])
            ->whereHas('aktivitas', function (Builder $q) {
                $q->where('sales_id', auth()->user()->id);
            })
            ->latest()->get();

        $aktivitas = $aktivitas->groupBy('aktivitas_id')->map(function ($data) {
            return $data->first();
        })->values();

        return DataTables::of($aktivitas)

            ->editColumn('tanggal', function ($data) {
                return \Carbon\Carbon::parse($data->tanggal)->format('d-m-Y');
            })
            ->editColumn('status', function ($data) {
                return Helpers::statusColour($data->status->nama_status ?? '');
            })
            ->editColumn('nominal', function ($data) {
                return Helpers::formatCurrency($data->nominal, 'Rp' ?? '');
            })
            ->addColumn('aktivity', function ($data) {
                return '
                <a href="sales/aktivitas/' . $data->aktivitas_id . '/history" class="btn btn-light-success btn-sm font-weight-bolder">Update</a>
                ';
            })
            ->addColumn('actions', function ($data) {
                return '
                <a href="/sales/aktivitas/' . $data->aktivitas_id . '/edit" class="d-inline">
                    <button class="btn btn-warning btn-sm"> <i class="far fa-edit text-white"></i></button>
                </a>
                <form action="/sales/aktivitas/' . $data->aktivitas_id . '" method="post" class="d-inline">
                    <input type="hidden" name="_method" value="delete">      
                    ' . @csrf_field() . '             
                    <button type="submit" class="hapus btn btn-danger btn-sm"><i class="far fa-trash-alt text-white"></i></button>
                </form> ';
            })
            ->rawColumns(['status', 'aktivity', 'actions'])
            ->toJson();
    }

    public function create()
    {
        $status = Status::all();

        $merchant = Merchant::with('salesHasMerchant', 'salesHasMerchant.userSales')
            ->whereHas('salesHasMerchant', function (Builder $q) {
                $q->where('sales_id', auth()->user()->id);
            })->get();

        return view('sales.aktivitas.addL', compact('merchant', 'status'));
    }

    public function store(Request $request) //create
    {
        $request->validate([
            // 'manajer_id' => 'required', 
            // 'sales_id' => 'required',
            // 'aktivitas_id => 'required',
            'merchant_id' => 'required|unique:aktivitas',
            'status_id' => 'required',
            'keterangan' => 'nullable',
            'nominal' => 'nullable',
            'foto' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'date' => 'required',
        ]);

        if ($request->file('foto')) {

            $image = $request->file('foto');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            Storage::disk('public')->put('aktivitas/' . $imageName, file_get_contents($image));

            $request['foto'] = $imageName ?? NULL;
        }

        //untuk ngambil id manajer dari salesnya
        $manajer = ManajerHasSales::where('sales_id', auth()->user()->id)->get();

        $aktivitas = Aktivitas::create([
            'manajer_id' => $manajer[0]->manajer_id, //pakai array biar yg keambil yg pertama
            'sales_id' => $request->user()->id,
            'merchant_id' => $request->merchant_id,
            'status_id' => $request->status_id,
        ]);

        $aktivitas_id = $aktivitas->id;

        LogAktivitas::create([
            'aktivitas_id' => $aktivitas_id,
            'status_id' => $request->status_id,
            'keterangan' => $request->keterangan,
            'nominal' => $request->nominal,
            'foto' => $request['foto'] = $imageName ?? NULL,
            'tanggal' => $request->date,
        ]);

        Alert::toast('Data Aktivitas Merchant Berhasil Di Tambahkan', 'success');

        return redirect('/sales/aktivitas/tableL');
    }

    //UPDATE
    public function history($aktivitas)
    {
        $status = Status::all();

        $history = LogAktivitas::with(['aktivitas', 'aktivitas.merchant'])->with(['status'])->where('aktivitas_id', $aktivitas)->get();

        return view('sales.aktivitas.history', compact('status', 'history'));
    }

    public function store1(Request $request) //nambah aktivitas
    {
        $request->validate([
            'aktivitas_id' => 'required',
            'status_id' => 'required',
            'keterangan' => 'nullable',
            'nominal' => 'nullable',
            'foto' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'date' => 'required',
        ]);

        if ($request->file('foto')) {

            $image = $request->file('foto');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            Storage::disk('public')->put('aktivitas/' . $imageName, file_get_contents($image));

            $request['foto'] = $imageName ?? NULL;
        }

        Aktivitas::where('id', $request->aktivitas_id)
            ->update([
                'status_id' => $request->status_id,
            ]);

        LogAktivitas::create([
            'aktivitas_id' => $request->aktivitas_id, //auto dapet aktivitas_id asal sama dg name di form
            'status_id' => $request->status_id,
            'keterangan' => $request->keterangan,
            'nominal' => $request->nominal,
            'foto' => $request['foto'] = $imageName ?? NULL,
            'tanggal' => $request->date,
        ]);

        Alert::toast('Data Aktivitas Merchant Berhasil Di Update', 'success');

        //stay ke halaman ini
        return back();
    }

    public function edit($aktivitas)
    {
        $status = Status::get();
        $history = LogAktivitas::with(['aktivitas', 'aktivitas.merchant', 'aktivitas.merchant.manajerHasMerchant', 'aktivitas.merchant.manajerHasMerchant.userManajer', 'aktivitas.merchant.salesHasMerchant', 'aktivitas.merchant.salesHasMerchant.userSales'])
            ->with(['status'])
            ->where('aktivitas_id', $aktivitas)->get();

        return view('sales.aktivitas.edit', compact('history', 'status'));
    }

    //edit/ubah aktivitas
    public function update(Request $request)
    {
        $request->validate([
            'status_id' => 'required',
            'keterangan' => 'required',
            'nominal' => 'required',
            // 'foto' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            'date' => 'required',
        ]);

        // dd($request);
        $no = 0;
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

            // PERUBAHAN UNTUK MENGEDIT 
            if ($value == last($request->input('id'))) {
                $logAktivitas = LogAktivitas::where('id', $value)->first();
                Aktivitas::where('id', $logAktivitas->aktivitas_id)->where('status_id', $logAktivitas->status_id)
                    ->update([
                        'status_id' =>  $request->input('status_id.' . $no),
                    ]);
            }

            $logAktivitas = LogAktivitas::where('id', $value)
                ->update([
                    'tanggal' => $request->input('date.' . $no),
                    'status_id' => $request->input('status_id.' . $no),
                    'keterangan' => $request->input('keterangan.' . $no),
                    'nominal' => $request->input('nominal.' . $no),
                ]);

            $no++;
        }

        Alert::toast('Data Aktivitas Merchant Berhasil Di Edit', 'success');

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

        Alert::toast('Data Aktivitas Merchant Berhasil Di Hapus', 'success');

        return redirect('/sales/aktivitas/tableL');
    }
}
