<?php

namespace App\Http\Controllers;

use App\Models\AreaLokasi;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use DataTables;

class AManajerController extends Controller
{
    public function index()
    {
        return view('admin.manajer.table');
    }

    public function indexData()
    {
        $manajer = User::with(['area_lokasi'])->where('role_id', 2)->latest()->get();

        return DataTables::of($manajer)
            ->addColumn('actions', function ($data) {
                return '
            <a href="admin/manajer/' . $data->id . '/edit" class="d-inline">
                <button class="btn btn-warning btn-sm"> <i class="far fa-edit text-white"></i></button>
            </a>
            <form action="/admin/manajer/' . $data->id . '" method="post" class="d-inline">
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
        $area_lokasi = AreaLokasi::get();

        return view('admin.manajer.add', compact('area_lokasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'area_id' => 'required|unique:users',
            'nama_user' => 'required',
            'nomor_telepon' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required',
            'foto' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        if ($request->file('foto')) {

            $image = $request->file('foto');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            Storage::disk('public')->put('manajer/' . $imageName, file_get_contents($image));

            $request['foto'] = $imageName ?? NULL;
        }

        User::create([
            'role_id' => 2,
            'area_id' => $request->area_id,
            'nama_user' => $request->nama_user,
            'nomor_telepon' => $request->nomor_telepon,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'foto' => $request['foto'] = $imageName ?? NULL,
        ]);

        Alert::toast('Data Manajer Berhasil Di Tambahkan', 'success');

        return redirect('/admin/manajer/table');
    }

    public function edit(User $user)
    {
        $area_lokasi = AreaLokasi::get();

        return view('admin.manajer.edit', compact('user', 'area_lokasi'));
    }

    public function resetPassword(User $user)
    {
        User::where('id', $user->id)
            ->update([
                'password' => bcrypt('manajer'),
            ]);

        Alert::toast('Password Manajer Berhasil Di Ubah', 'success');

        return redirect('/admin/manajer/' . $user->id . '/edit');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'area_id' => 'required',
            'nama_user' => 'required',
            'nomor_telepon' => 'required',
        ]);

        // unique area_id
        if ($user->area_id != $request->area_id) {
            $area = User::all();
            foreach ($area as $ar) {
                if ($ar->area_id == $request->area_id) {
                    return redirect('admin/manajer/' . $user->id . '/edit')->with('area_id', 'The area id has already been taken.');
                }
            }
        }

        // unique nomor telepon
        if ($user->nomor_telepon != $request->nomor_telepon) {
            $nomor = User::all();
            foreach ($nomor as $us) {
                if ($us->nomor_telepon == $request->nomor_telepon) {
                    return redirect('admin/manajer/' . $user->id . '/edit')->with('nomor_telepon', 'The nomor telepon has already been taken.');
                }
            }
        }

        User::where('id', $user->id)
            ->update([
                'area_id' => $request->area_id,
                'nama_user' => $request->nama_user,
                'nomor_telepon' => $request->nomor_telepon,
            ]);

        Alert::toast('Data Manajer Berhasil Di Edit', 'success');

        return redirect('/admin/manajer/table');
    }

    public function destroy(User $user)
    {
        //hanya hapus manajer, relasinya gausah dihapus
        User::destroy($user->id);

        Alert::toast('Data Manajer Berhasil Di Hapus', 'success');

        return redirect('/admin/manajer/table');
    }

    public function exportExcel()
    {

        $user = User::with(['area_lokasi'])->latest()->get();

        $user = $user->whereNotIn('role_id', [1, 3])->values();

        return view('admin.manajer.exportExcel', compact('user'));
    }

    public function exportPdf()
    {

        $user = User::with(['area_lokasi'])->latest()->get();

        $user = $user->whereNotIn('role_id', [1, 3])->values();

        return view('admin.manajer.exportPdf', compact('user'));
    }


    public function trash()
    {
        return view('admin.manajer.trash');
    }

    public function trashData()
    {
        //menampilkan data yg terhapus sementara
        $manajer = User::onlyTrashed()->with(['area_lokasi'])->where('role_id', 2)->latest()->get();

        return DataTables::of($manajer)
            ->addColumn('actions', function ($data) {
                return '
            <a href="admin/manajer/restore/' . $data->id . '" class="d-inline">
                <button class="btn btn-success btn-sm">Pulihkan
                </button>
            </a>
            <form action="/admin/manajer/delete/' . $data->id . '" method="post" class="d-inline">
                <input type="hidden" name="_method" value="delete">
                ' . @csrf_field() . ' 
                <button type="submit" class="hapus btn btn-danger btn-sm">Hapus Permanen</button>
            </form>
            ';
            })
            ->rawColumns(['actions'])
            ->toJson();
    }

    public function restore($id = null)
    {
        if ($id != null) {
            //restore berdasarkan id
            $manajer = User::onlyTrashed()->with(['area_lokasi'])->where('role_id', 2)
                ->where('id', $id)->restore();

            if ($manajer != null) {
                Alert::toast('Data Manajer Berhasil Di Pulihkan', 'success');
            }
        } else {
            //restore semua data
            $manajer = User::onlyTrashed()->with(['area_lokasi'])->where('role_id', 2)->restore();

            if ($manajer != null) {
                Alert::toast('Semua Data Manajer Berhasil Di Pulihkan', 'success');
            }
        }

        return redirect('/admin/manajer/trash');
    }

    public function delete($id = null)
    {
        //delete permanen
        if ($id != null) {
            $manajer = User::onlyTrashed()->with(['area_lokasi'])->where('role_id', 2)
                ->where('id', $id)->forceDelete();

            if ($manajer != null) {
                Alert::toast('Data Manajer Berhasil Di Hapus Permanen', 'success');
            }
        } else {
            $manajer = User::onlyTrashed()->with(['area_lokasi'])->where('role_id', 2)->forceDelete();

            if ($manajer != null) {
                Alert::toast('Semua Data Manajer Berhasil Di Hapus Permanen', 'success');
            }
        }

        return redirect('/admin/manajer/trash');
    }

    public function trashPdf()
    {
        $manajer = User::onlyTrashed()->with(['area_lokasi'])->where('role_id', 2)->latest()->get();

        return view('admin.manajer.trashPdf', compact('manajer'));
    }

    public function trashExcel()
    {
        $manajer = User::onlyTrashed()->with(['area_lokasi'])->where('role_id', 2)->latest()->get();

        return view('admin.manajer.trashExcel', compact('manajer'));
    }
}
