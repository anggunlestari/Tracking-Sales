<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Merchant;
use App\Models\User;
use App\Models\City;
use App\Models\District;
use App\Models\Aktivitas;
use App\Models\LogAktivitas;
use App\Models\ManajerHasMerchant;
use App\Models\ManajerHasSales;
use App\Models\Province;
use App\Models\SalesHasMerchant;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use DataTables;

class AMerchantController extends Controller
{
    public function index()
    {
        return view('admin.merchant.table');
    }

    public function indexData()
    {
        $merchant = Merchant::with(['aktivitas', 'aktivitas.logAktivitas'])
            ->with(['manajerHasMerchant', 'manajerHasMerchant.userManajer'])
            ->with(['salesHasMerchant', 'salesHasMerchant.userSales'])->latest()->get();

        $merchant = $merchant->groupBy('id')->map(function ($data) {
            return $data->first();
        })->values();

        //detail data
        //     <button data-toggle="modal" data-target="#view-modal' . $data->id . '"
        //     class="btn btn-primary btn-sm"> <i class=" far fa-eye text-white text-center"></i>
        // </button>

        return DataTables::of($merchant)
            ->addColumn('aktivitas', function ($data) {
                if ($data->id != "") $history = 'admin/merchant/' . $data->id . '/history';
                else $history = 'admin/merchant/table';
                return  '
                <a href="' . $history . '"
                class="btn btn-light-success btn-sm font-weight-bolder">History</a>
                ';
            })

            ->addColumn('actions', function ($data) {
                return  '
            <a href="/admin/merchant/' . $data->id . '/edit" class="d-inline">
                <button class="btn btn-warning btn-sm"> <i class="far fa-edit text-white"></i></button>
            </a>
            <form action="/admin/merchant/' . $data->id . '" method="post"
                class="d-inline">
                <input type="hidden" name="_method" value="delete">
                ' . @csrf_field() . '
                <button type="submit" class="hapus btn btn-danger btn-sm"><i
                        class="far fa-trash-alt text-white"></i></button>
            </form>';
            })
            ->rawColumns(['aktivitas', 'actions'])
            ->toJson();
    }

    public function create()
    {
        $kategori = Kategori::get();

        $provincedr = Province::pluck('name', 'id');
        $manajerdr = User::where('role_id', 2)->pluck('nama_user', 'id');

        return view('admin.merchant.add', compact('kategori', 'provincedr', 'manajerdr'));
    }

    public function getSales($id)
    {
        $salesdr = collect(ManajerHasSales::with(['userSales'])->where("manajer_id", $id)->get()->toArray())->map(function ($item, $key) {
            $result['name'] = $item['user_sales']['nama_user'];
            $result['id'] = $item['user_sales']['id'];
            return $result;
        })->values();

        return json_encode($salesdr);
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
            'manajer_id' => 'required',
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
            'manajer_id' => $request->manajer_id,
            'merchant_id' => $merchant_id,
        ]);

        SalesHasMerchant::create([
            'sales_id' => $request->sales_id,
            'merchant_id' => $merchant_id,
        ]);

        Alert::toast('Data Merchant Berhasil Di Tambahkan', 'success');

        return redirect('/admin/merchant/table');
    }

    public function edit($merchant)
    {
        $kategori = Kategori::get();
        $province = Province::get();
        $city = City::get();
        $district = District::get();

        //get manajernya sesuai dg id merchant
        $manajer = Merchant::where('id', $merchant)->with(['manajerHasMerchant.userManajer'])->first();

        //get sales berdasarkan manajer_id nya
        $sales = ManajerHasSales::with('userSales')->where('manajer_id', $manajer->manajerHasMerchant->manajer_id)->get();
        // return $sales;

        $merchant = Merchant::where('id', $merchant)->with(['manajerHasMerchant', 'manajerHasMerchant.userManajer'])
            ->with(['salesHasMerchant', 'salesHasMerchant.userSales'])->first();
        // return $merchant;

        return view('admin.merchant.edit', compact('merchant', 'manajer', 'sales', 'kategori', 'province', 'city', 'district'));
    }

    //AMAN
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

        // unique nomor telepon
        if ($merchant->nomor_telepon != $request->nomor_telepon) {
            $nomor = Merchant::all();
            foreach ($nomor as $us) {
                if ($us->nomor_telepon == $request->nomor_telepon) {
                    return redirect('admin/merchant/' . $merchant->id . '/edit')->with('nomor_telepon', 'The nomor telepon has already been taken.');
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
        // ManajerHasSales : gaperlu karna udah ada relasinya > auto ngambil sales dari manajer yg sama (karna selected)

        SalesHasMerchant::where('merchant_id', $merchant->id)
            ->update([
                'sales_id' => $request->sales_id,
            ]);

        // ngubah sales_id di satu merchant spesifik > pake first dan gapake sama aja karna udh ngarah lngsng spesifik ke merchantnya
        $aktivitas = Aktivitas::where('merchant_id', $merchant->id)->get();
        if (!empty($aktivitas)) {
            Aktivitas::where('merchant_id', $merchant->id)
                ->update([
                    'sales_id' => $request->sales_id,
                ]);
        }

        Alert::toast('Data Merchant Berhasil Di Edit', 'success');

        return redirect('/admin/merchant/table');
    }

    //error
    public function destroy(Merchant $merchant)
    {
        //delete LogAktivitas | pake first biar lngsung ke index yg mau dihapus , kalo get aja dia isinya banyak array
        $aktivitas = Aktivitas::where('merchant_id', $merchant->id)->get()->first();

        if (!empty($aktivitas)) {        // if (count($aktivitas) > 0) {
            $logAktivitas = LogAktivitas::where('aktivitas_id', $aktivitas->id)->get(['id']);
            foreach ($logAktivitas as $aktiv) {
                LogAktivitas::destroy($aktiv->toArray());
            }
        }

        //delete table aktivitas
        $aktivitas = Aktivitas::where('merchant_id', $merchant->id)->get(['id']);
        Aktivitas::destroy($aktivitas->toArray());

        //delete table sales_has_merchant
        $salesmerchant = SalesHasMerchant::where('merchant_id', $merchant->id)->get(['id']);
        SalesHasMerchant::destroy($salesmerchant->toArray());

        //delete table manajer_has_merchant
        $manajermerchant = ManajerHasMerchant::where('merchant_id', $merchant->id)->get(['id']);
        ManajerHasMerchant::destroy($manajermerchant->toArray());

        //delete table merchant
        Merchant::destroy($merchant->id);

        Alert::toast('Data Merchant Berhasil Di Hapus', 'success');

        return redirect('/admin/merchant/table');
    }


    public function exportExcel()
    {
        $merchant = Merchant::with(['aktivitas', 'aktivitas.logAktivitas'])
            ->with(['manajerHasMerchant', 'manajerHasMerchant.userManajer'])
            ->with(['salesHasMerchant', 'salesHasMerchant.userSales'])
            ->with(['kategori'])->with(['province'])->with(['district'])->with(['city'])->latest()->get();

        return view('admin.merchant.exportExcel', compact('merchant'));
    }

    public function exportPdf()
    {
        $merchant = Merchant::with(['aktivitas', 'aktivitas.logAktivitas'])
            ->with(['manajerHasMerchant', 'manajerHasMerchant.userManajer'])
            ->with(['salesHasMerchant', 'salesHasMerchant.userSales'])
            ->with(['kategori'])->with(['province'])->with(['district'])->with(['city'])->latest()->get();

        return view('admin.merchant.exportPdf', compact('merchant'));
    }


    public function history($aktivitas)
    {
        $history = LogAktivitas::with(['aktivitas', 'aktivitas.merchant', 'aktivitas.merchant.salesHasMerchant', 'aktivitas.merchant.salesHasMerchant.userSales', 'aktivitas.merchant.manajerHasMerchant', 'aktivitas.merchant.manajerHasMerchant.userManajer'])
            ->with(['status'])
            ->whereHas('aktivitas', function (Builder $q) use ($aktivitas) {
                $q->where('merchant_id', $aktivitas);
            })
            ->get();

        // untuk mengecek aktivitas kosong (nampilin alert) atau tidak [ngambil nya dari id_merchant bukan id_aktivitas]
        if (empty($history[0]->id)) {
            Alert::error('Info', 'Belum Ada Aktivitas yang Ditambahkan');
            return redirect()->back();
            // return redirect()->back()->with('status', 'Belum Ada Aktivitas yang Ditambahkan!');
        } else {
            return view('admin.merchant.history', compact('history'));
        }
    }

    public function excelHistory($aktivitas)
    {
        $history = LogAktivitas::with(['aktivitas', 'aktivitas.merchant', 'aktivitas.merchant.salesHasMerchant', 'aktivitas.merchant.salesHasMerchant.userSales', 'aktivitas.merchant.manajerHasMerchant', 'aktivitas.merchant.manajerHasMerchant.userManajer'])
            ->with(['status'])
            ->where('aktivitas_id', $aktivitas)->get();

        return view('admin.merchant.historyExcel', compact('history'));
    }

    public function pdfHistory($aktivitas)
    {
        $history = LogAktivitas::with(['aktivitas', 'aktivitas.merchant', 'aktivitas.merchant.salesHasMerchant', 'aktivitas.merchant.salesHasMerchant.userSales', 'aktivitas.merchant.manajerHasMerchant', 'aktivitas.merchant.manajerHasMerchant.userManajer'])
            ->with(['status'])
            ->where('aktivitas_id', $aktivitas)->get();

        return view('admin.merchant.historyPdf', compact('history'));
    }
}
