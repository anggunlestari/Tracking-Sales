<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kategori;
use App\Models\City;
use App\Models\District;
use App\Models\ManajerHasMerchant;
use App\Models\Province;
use App\Models\SalesHasMerchant;
use App\Models\Aktivitas;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use App\Models\LogAktivitas;
use DataTables;

class MMerchantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('manajer.merchant.table');
    }

    public function indexData()
    {
        $merchant = Merchant::with(['aktivitas', 'aktivitas.logAktivitas'])
            ->with(['manajerHasMerchant', 'manajerHasMerchant.userManajer'])
            ->with(['salesHasMerchant', 'salesHasMerchant.userSales'])
            ->whereHas('manajerHasMerchant', function (Builder $q) {
                $q->where('manajer_id', auth()->user()->id);
            })->latest()->get();

        return DataTables::of($merchant)
            ->addColumn('aktivitas', function ($data) {
                if ($data->id != "") $history = 'manajer/merchant/' . $data->id . '/history';
                else $history = 'manajer/merchant/table';
                return '
               <a href="' . $history . '" class="btn btn-light-success btn-sm font-weight-bolder">History</a></td>
                ';
            })
            ->addColumn('actions', function ($data) {
                return '
                <a href="/manajer/merchant/' . $data->id . '/edit" class="d-inline">
                    <button class="btn btn-warning btn-sm">
                        <i class="far fa-edit text-white"></i></button>
                </a>
                <form action="/manajer/merchant/' . $data->id . '" method="post" class="d-inline">
                    <input type="hidden" name="_method" value="delete">
                    ' . @csrf_field() . '
                    <button type="submit" class="hapus btn btn-danger btn-sm"><i
                            class="far fa-trash-alt text-white"></i></button>
                </form>
                ';
            })
            ->rawColumns(['aktivitas', 'actions'])
            ->toJson();
    }

    public function create()
    {
        $sales = User::with(['manajerHasSales.userSales'])->where('role_id', 3)
            ->whereHas('manajerHasSales', function (Builder $q) {
                $q->where('manajer_id', auth()->user()->id);
            })->get();

        $kategori = Kategori::get();
        $provincedr = Province::pluck('name', 'id');

        return view('manajer.merchant.add', compact('sales', 'kategori', 'provincedr'));
    }

    public function getCity($id)
    {
        $citydr = City::where("province_id", $id)->pluck("name", "id");
        return json_encode($citydr);
    }

    public function getDistrict($id)
    {
        $districtdr = District::where("city_id", $id)->pluck("name", "id");
        return json_encode($districtdr);
    }

    public function store(Request $request)
    {
        $request->validate([
            // 'manajer_id' => 'required',
            'sales_id' => 'required',
            'nama_merchant' => 'required',
            'kategori_id' => 'required',
            'nama_pemilik' => 'required',
            'nomor_telepon' => 'required|unique:merchant',
            'alamat' => 'required',
            'province_id' => 'required',
            'city_id' => 'required',
            'district_id' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'foto' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->file('foto')) {

            $image = $request->file('foto');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            Storage::disk('public')->put('merchant/' . $imageName, file_get_contents($image));

            $request['foto'] = $imageName ?? NULL;
        }

        $merchant =  Merchant::create([
            'nama_merchant' => $request->nama_merchant,
            'kategori_id' => $request->kategori_id,
            'nama_pemilik' => $request->nama_pemilik,
            'nomor_telepon' => $request->nomor_telepon,
            'alamat' => $request->alamat,
            'province_id' => $request->province_id,
            'city_id' => $request->city_id,
            'district_id' => $request->district_id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'foto' => $request['foto'] = $imageName ?? NULL,
        ]);

        $merchant_id = $merchant->id;

        ManajerHasMerchant::create([
            'manajer_id' => $request->user()->id,
            'merchant_id' => $merchant_id,
        ]);

        SalesHasMerchant::create([
            'sales_id' => $request->sales_id,
            'merchant_id' => $merchant_id,
        ]);

        Alert::toast('Data Merchant Berhasil Di Tambahkan', 'success');

        return redirect('/manajer/merchant/table');
    }

    public function edit($merchant)
    {
        $kategori = Kategori::get();
        $province = Province::get();
        $city = City::get();
        $district = District::get();

        $sales = User::with(['salesHasMerchant', 'salesHasMerchant.userSales'])
            ->with(['manajerHasSales'])->where('role_id', 3)
            ->whereHas('manajerHasSales', function (Builder $q) {
                $q->where('manajer_id', auth()->user()->id);
            })->get();

        $merchant = Merchant::with(['manajerHasMerchant', 'manajerHasMerchant.userManajer'])
            ->with(['salesHasMerchant', 'salesHasMerchant.userSales'])->where('id', $merchant)->first();
        // return $merchant;

        $provincedr = Province::pluck("name", 'id');

        return view('manajer.merchant.edit', compact('sales', 'merchant', 'kategori', 'province', 'city', 'district', 'provincedr'));
    }

    // AMAN = SAMA KAYA AMerchantController
    public function update(Request $request, Merchant $merchant)
    {
        $request->validate([
            // 'manajer_id' => 'required',
            'sales_id' => 'required',
            'nama_merchant' => 'required',
            'kategori_id' => 'required',
            'nama_pemilik' => 'required',
            'nomor_telepon' => 'required',
            'alamat' => 'required',
            'province_id' => 'required',
            'city_id' => 'required',
            'district_id' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'foto' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->file('foto')) {

            $image = $request->file('foto');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            Storage::disk('public')->put('merchant/' . $imageName, file_get_contents($image));

            $request['foto'] = $imageName ?? $merchant->foto;
        }

        //unique nomor telepon
        if ($merchant->nomor_telepon != $request->nomor_telepon) {
            $nomor = Merchant::all();
            foreach ($nomor as $no) {
                if ($no->nomor_telepon == $request->nomor_telepon) {
                    return redirect('manajer/merchant/' . $merchant->id . '/edit')->with('nomor_telepon', 'The nomor telepon has already been taken.');
                }
            }
        }

        Merchant::where('id', $merchant->id)
            ->update([
                'nama_merchant' => $request->nama_merchant,
                'kategori_id' => $request->kategori_id,
                'nama_pemilik' => $request->nama_pemilik,
                'nomor_telepon' => $request->nomor_telepon,
                'alamat' => $request->alamat,
                'province_id' => $request->province_id,
                'city_id' => $request->city_id,
                'district_id' => $request->district_id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'foto' => $request['foto'] = $imageName ?? $merchant->foto,
            ]);

        SalesHasMerchant::where('merchant_id', $merchant->id)
            ->update([
                'sales_id' => $request->sales_id,
            ]);

        // ngecek apakah udah punya aktivitas
        $aktivitas = Aktivitas::where('merchant_id', $merchant->id)->get();
        if (!empty($aktivitas)) {
            Aktivitas::where('merchant_id', $merchant->id)
                ->update([
                    'sales_id' => $request->sales_id,
                ]);
        }

        Alert::toast('Data Merchant Berhasil Di Edit', 'success');

        return redirect('/manajer/merchant/table');
    }

    //error
    public function destroy(Merchant $merchant)
    {
        //delete LogAktivitas
        $aktivitas = Aktivitas::where('merchant_id', $merchant->id)->get()->first();

        if (!empty($aktivitas)) {        // if (count($aktivitas) > 0) {
            $logAktivitas = LogAktivitas::where('aktivitas_id', $aktivitas->id)->get(['id']);
            foreach ($logAktivitas as $aktiv) {
                LogAktivitas::destroy($aktiv->toArray());
            }
        }

        // delete Aktivitas
        $aktivitas = Aktivitas::where('merchant_id', $merchant->id)->get(['id']);
        Aktivitas::destroy($aktivitas->toArray());

        $salesmerchant = SalesHasMerchant::where('merchant_id', $merchant->id)->get(['id']);
        SalesHasMerchant::destroy($salesmerchant->toArray());

        $manajermerchant = ManajerHasMerchant::where('merchant_id', $merchant->id)->get(['id']);
        ManajerHasMerchant::destroy($manajermerchant->toArray());

        Merchant::destroy($merchant->id);

        Alert::toast('Data Merchant Berhasil Di Hapus', 'success');

        return redirect('/manajer/merchant/table');
    }

    public function exportExcel()
    {
        $merchant = Merchant::with(['manajerHasMerchant', 'manajerHasMerchant.userManajer'])->with(['salesHasMerchant', 'salesHasMerchant.userSales'])->whereHas('manajerHasMerchant', function (Builder $q) {
            $q->where('manajer_id', auth()->user()->id);
        })->latest()->get();

        $kategori = Kategori::get();
        $province = Province::get();
        $city = City::get();
        $district = District::get();

        return view('manajer.merchant.exportExcel', compact('merchant', 'kategori', 'province', 'city', 'district'));
    }

    public function exportPdf()
    {
        $merchant = Merchant::with(['manajerHasMerchant', 'manajerHasMerchant.userManajer'])->with(['salesHasMerchant', 'salesHasMerchant.userSales'])->whereHas('manajerHasMerchant', function (Builder $q) {
            $q->where('manajer_id', auth()->user()->id);
        })->latest()->get();

        $kategori = Kategori::get();
        $province = Province::get();
        $city = City::get();
        $district = District::get();

        return view('manajer.merchant.exportPdf', compact('merchant', 'kategori', 'province', 'city', 'district'));
    }

    public function history($aktivitas)
    {
        $history = LogAktivitas::with(['aktivitas', 'aktivitas.merchant', 'aktivitas.merchant.salesHasMerchant', 'aktivitas.merchant.salesHasMerchant.userSales'])
            ->with(['status'])
            ->whereHas('aktivitas', function (Builder $q) use ($aktivitas) {
                $q->where('merchant_id', $aktivitas);
            })
            ->get();

        // untuk mengecek aktivitas kosong (nampilin alert) atau tidak [ngambil nya dari id_merchant bukan id_aktivitas]
        if (empty($history[0]->id)) {
            Alert::error('Info', 'Belum Ada Aktivitas yang Ditambahkan');
            return redirect()->back();
        } else {
            return view('manajer.merchant.history', compact('history'));
        }
    }

    public function excelHistory($aktivitas)
    {
        $history = LogAktivitas::with(['aktivitas', 'aktivitas.merchant', 'aktivitas.merchant.salesHasMerchant', 'aktivitas.merchant.salesHasMerchant.userSales'])
            ->with(['status'])
            ->where('aktivitas_id', $aktivitas)->get();

        return view('manajer.merchant.historyExcel', compact('history'));
    }

    public function pdfHistory($aktivitas)
    {
        $history = LogAktivitas::with(['aktivitas', 'aktivitas.merchant', 'aktivitas.merchant.salesHasMerchant', 'aktivitas.merchant.salesHasMerchant.userSales'])
            ->with(['status'])
            ->where('aktivitas_id', $aktivitas)->get();

        return view('manajer.merchant.historyPdf', compact('history'));
    }
}
