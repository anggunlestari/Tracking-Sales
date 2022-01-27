<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\City;
use App\Models\District;
use App\Models\ManajerHasMerchant;
use App\Models\ManajerHasSales;
use App\Models\Province;
use App\Models\SalesHasMerchant;
use App\Models\Aktivitas;
use App\Models\LogAktivitas;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use DataTables;

class SMerchantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('sales.merchant.table');
    }

    public function indexData()
    {
        $merchant = Merchant::with(['manajerHasMerchant', 'manajerHasMerchant.userManajer'])
            ->with(['salesHasMerchant', 'salesHasMerchant.userSales'])
            ->whereHas('salesHasMerchant', function (Builder $q) {
                $q->where('sales_id', auth()->user()->id);
            })->latest()->get();

        return DataTables::of($merchant)
            ->addColumn('actions', function ($data) {
                return '
            <a href="/sales/merchant/' . $data->id . '/edit" class="d-inline">
                <button class="btn btn-warning btn-sm">
                    <i class="far fa-edit text-white"></i></button>
            </a>
            <form action="/sales/merchant/' . $data->id . '" method="post" class="d-inline">
                <input type="hidden" name="_method" value="delete">
                ' . @csrf_field() . '
                <button type="submit" class="hapus btn btn-danger btn-sm"><i
                        class="far fa-trash-alt text-white"></i></button>
            </form>
            ';
            })
            ->rawColumns(['actions'])
            ->toJson();
    }

    public function create()
    {
        $kategori = Kategori::get();

        $provincedr = Province::pluck('name', 'id');

        $manajer = ManajerHasSales::where('sales_id', auth()->user()->id)->get();

        return view('sales.merchant.add', compact('kategori', 'provincedr', 'manajer'));
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
            // 'sales_id' => 'required',
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

        SalesHasMerchant::create([
            'sales_id' => $request->user()->id,
            'merchant_id' => $merchant_id,
        ]);

        $manajer = ManajerHasSales::where('sales_id', auth()->user()->id)->get();

        ManajerHasMerchant::create([
            'manajer_id' => $manajer[0]->manajer_id,
            'merchant_id' => $merchant_id,
        ]);

        Alert::toast('Data Merchant Berhasil Di Tambahkan', 'success');

        return redirect('/sales/merchant/table');
    }

    public function edit($merchant)
    {
        $kategori = Kategori::get();
        $province = Province::get();
        $city = City::get();
        $district = District::get();

        $provincedr = Province::pluck("name", 'id');

        $merchant = Merchant::where('id', $merchant)->with(['manajerHasMerchant', 'manajerHasMerchant.userManajer'])->with(['salesHasMerchant', 'salesHasMerchant.userSales'])->first();

        return view('sales.merchant.edit', compact('merchant', 'kategori', 'province', 'city', 'district', 'provincedr'));
    }


    public function update(Request $request, Merchant $merchant)
    {
        $request->validate([
            // 'manajer_id' => 'required',
            // 'sales_id' => 'required',
            'nama_merchant' => 'required',
            'kategori_id' => 'required',
            'nama_pemilik' => 'required',
            'nomor_telepon' => 'required',
            'alamat' => 'required',
            'province_id' => 'required',
            'city_id' => 'required',
            'district_id' => 'required',
            'foto' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->file('foto')) {

            $image = $request->file('foto');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            Storage::disk('public')->put('merchant/' . $imageName, file_get_contents($image));

            $request['foto'] = $imageName ?? $merchant->foto;
        }

        // unique nomor telepon
        if ($merchant->nomor_telepon != $request->nomor_telepon) {
            $nomor = Merchant::all();
            foreach ($nomor as $no) {
                if ($no->nomor_telepon == $request->nomor_telepon) {
                    return redirect('sales/merchant/' . $merchant->id . '/edit')->with('nomor_telepon', 'The nomor telepon has already been taken.');
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
                'foto' => $request['foto'] = $imageName ?? $merchant->foto,
            ]);

        Alert::toast('Data Merchant Berhasil Di Edit', 'success');

        return redirect('/sales/merchant/table');
    }

    public function destroy(Merchant $merchant)
    {
        //delete logAktivitas
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

        return redirect('/sales/merchant/table');
    }

    public function exportExcel()
    {
        $merchant = Merchant::with(['manajerHasMerchant', 'manajerHasMerchant.userManajer'])->with(['salesHasMerchant', 'salesHasMerchant.userSales'])->whereHas('salesHasMerchant', function (Builder $q) {
            $q->where('sales_id', auth()->user()->id);
        })->latest()->get();

        $kategori = Kategori::get();
        $province = Province::get();
        $city = City::get();
        $district = District::get();

        return view('sales.merchant.exportExcel', compact('merchant', 'kategori', 'province', 'city', 'district'));
    }

    public function exportPdf()
    {
        $merchant = Merchant::with(['manajerHasMerchant', 'manajerHasMerchant.userManajer'])->with(['salesHasMerchant', 'salesHasMerchant.userSales'])->whereHas('salesHasMerchant', function (Builder $q) {
            $q->where('sales_id', auth()->user()->id);
        })->latest()->get();

        $kategori = Kategori::get();
        $province = Province::get();
        $city = City::get();
        $district = District::get();

        return view('sales.merchant.exportPdf', compact('merchant', 'kategori', 'province', 'city', 'district'));
    }
}
