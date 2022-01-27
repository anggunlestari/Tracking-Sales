<?php

namespace App\Http\Controllers;

use App\Models\AreaLokasi;
use App\Models\City;
use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use DataTables;

class AAreaLokasiController extends Controller
{
    public function index()
    {
        //disamain tipe datanya dan foreignya di hapus
        return view('admin.areaLokasi.table');
    }

    public function indexData()
    {
        $area_lokasi = AreaLokasi::with(['province', 'province.city', 'province.city.district'])->latest()->get();

        return DataTables::of($area_lokasi)
            ->addColumn('actions', function ($data) {
                return '
                <a href="/admin/areaLokasi/' .  $data->id . '/edit" class="d-inline">
                    <button class="btn btn-warning btn-sm"> <i class="far fa-edit text-white"></i></button>
                </a>
                <form action="/admin/areaLokasi/' . $data->id . '" method="post" class="d-inline">
                    <input type="hidden" name="_method" value="delete">   
                ' . @csrf_field() . '
                    <button type="submit" class="hapus btn btn-danger btn-sm"><i class="far fa-trash-alt text-white"></i></button>
                </form> 
               ';
            })
            ->rawColumns(['actions'])
            ->toJson();
    }

    public function create()
    {
        $province = Province::get();
        $city = City::get();
        $district = District::get();

        $provincedr = Province::pluck("name", 'id');

        return view('admin.areaLokasi.add', compact('province', 'city', 'district', 'provincedr'));
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
            'nama_area' => 'required|unique:area_lokasi',
            'province_id' => 'required',
            'city_id' => 'required',
            'district_id' => 'required',
        ]);

        AreaLokasi::create($request->all());
        Alert::toast('Data Area Lokasi Berhasil Di Tambahkan', 'success');

        return redirect('/admin/areaLokasi/table');
    }

    public function edit(AreaLokasi $areaLokasi)
    {
        $province = Province::get();
        $city = City::get();
        $district = District::get();

        $provincedr = Province::pluck("name", 'id');

        return view('admin.areaLokasi.edit', compact('areaLokasi', 'province', 'city', 'district', 'provincedr'));
    }

    public function update(Request $request, AreaLokasi $areaLokasi)
    {
        $request->validate([
            'nama_area' => 'required',
            'province_id' => 'required',
            'city_id' => 'required',
            'district_id' => 'required',
        ]);

        //unique nama_area
        if ($areaLokasi->nama_area != $request->nama_area) {
            $area = AreaLokasi::all();
            foreach ($area as $ar) {
                if ($ar->nama_area == $request->nama_area) {
                    return redirect('admin/areaLokasi/' . $areaLokasi->id . '/edit')->with('nama_area', 'The nama area has already been taken.');
                }
            }
        }

        AreaLokasi::where('id', $areaLokasi->id)
            ->update([
                'nama_area' => $request->nama_area,
                'province_id' => $request->province_id,
                'city_id' => $request->city_id,
                'district_id' => $request->district_id,
            ]);

        Alert::toast('Data Area Lokasi Berhasil Di Edit', 'success');

        return redirect('/admin/areaLokasi/table');
    }

    public function destroy(AreaLokasi $areaLokasi)
    {
        AreaLokasi::destroy($areaLokasi->id);

        Alert::toast('Data Area Lokasi Berhasil Di Hapus', 'success');

        return redirect('/admin/areaLokasi/table');
    }

    public function exportExcel()
    {
        $area_lokasi = AreaLokasi::with(['province'])->with(['district'])->with(['city'])->latest()->get();

        return view('admin.areaLokasi.exportExcel', compact('area_lokasi'));
    }

    public function exportPdf()
    {
        $area_lokasi = AreaLokasi::with(['province'])->with(['district'])->with(['city'])->latest()->get();

        return view('admin.areaLokasi.exportPdf', compact('area_lokasi'));
    }
}
