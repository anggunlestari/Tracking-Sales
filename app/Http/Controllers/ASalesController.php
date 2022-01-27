<?php

namespace App\Http\Controllers;

use App\Models\Aktivitas;
use App\Models\ManajerHasSales;
use App\Models\ManajerHasMerchant;
use App\Models\SalesHasMerchant;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use DataTables;

class ASalesController extends Controller
{
    public function index()
    {
        return view('admin.sales.table');
    }

    public function indexData()
    {
        $sales = User::with(['manajerHasSales', 'manajerHasSales.userManajer'])->where('role_id', 3)->latest()->get();

        return DataTables::of($sales)
            ->addColumn('actions', function ($data) {
                return '
                <a href="admin/sales/' . $data->id . '/edit" class="d-inline">
                    <button class="btn btn-warning btn-sm"> <i class="far fa-edit text-white"></i></button>
                </a>
                <form action="/admin/sales/' . $data->id . '" method="post" class="d-inline">
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
        $manajer = User::where('role_id', 2)->get();

        return view('admin.sales.add', compact('manajer'));
    }

    public function store(Request $request)
    {
        $request->validate([
            // 'role_id' => 'required',
            'manajer_id' => 'required',
            'nama_user' => 'required',
            'nomor_telepon' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required',
            'foto' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->file('foto')) {

            $image = $request->file('foto');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            Storage::disk('public')->put('sales/' . $imageName, file_get_contents($image));

            $request['foto'] = $imageName ?? NULL;
        }

        $sales =  User::create([
            'role_id' => 3,
            'nama_user' => $request->nama_user,
            'nomor_telepon' => $request->nomor_telepon,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'foto' => $request['foto'] = $imageName ?? NULL,
        ]);

        $sales_id = $sales->id;

        ManajerHasSales::create([
            'manajer_id' => $request->manajer_id,
            'sales_id' => $sales_id,
        ]);

        Alert::toast('Data Sales Berhasil Di Tambahkan', 'success');

        return redirect('/admin/sales/table');
    }

    public function edit($user)
    {
        $manajer = User::where('role_id', 2)->with(['manajerHasSales', 'manajerHasSales.userManajer'])->get();

        $user = User::where('id', $user)->with(['manajerHasSales.userManajer'])->first();

        return view('admin.sales.edit', compact('user', 'manajer'));
    }

    public function resetPassword(User $user)
    {
        User::where('id', $user->id)
            ->update([
                'password' => bcrypt('sales'),
            ]);

        Alert::toast('Password Sales Berhasil Di Ubah', 'success');

        return redirect('/admin/sales/' . $user->id . '/edit');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            // 'manajer_id' => 'required',
            'nama_user' => 'required',
            'nomor_telepon' => 'required',
        ]);

        // unique nomor telepon
        if ($user->nomor_telepon != $request->nomor_telepon) {
            $nomor = User::all();
            foreach ($nomor as $us) {
                if ($us->nomor_telepon == $request->nomor_telepon) {
                    return redirect('admin/sales/' . $user->id . '/edit')->with('nomor_telepon', 'The nomor telepon has already been taken.');
                }
            }
        }

        User::where('id', $user->id)
            ->update([
                'nama_user' => $request->nama_user,
                'nomor_telepon' => $request->nomor_telepon,
            ]);

        ManajerHasSales::where('sales_id', $user->id)
            ->update([
                'manajer_id' => $request->manajer_id,
            ]);

        $merchant = SalesHasMerchant::where('sales_id', $user->id)->get();
        // dd($merchant);

        // ngecek apakah sales udah punya merchant
        if (!empty($merchant)) {
            // banyak merchant (dari satu sales) ke manajer, pake foreach
            foreach ($merchant as $value) {
                ManajerHasMerchant::where('merchant_id', $value->merchant_id)
                    ->update([
                        'manajer_id' => $request->manajer_id,
                    ]);
            }
        }

        // ngecek apakah sales udah punya aktivitas
        $aktivitas = Aktivitas::where('sales_id', $user->id)->get();
        if (!empty($aktivitas)) {
            // banyak aktiivitas (berdasar sales_id : dari satu sales punya bnyk merchant) ke manajer
            foreach ($merchant as $value) {
                Aktivitas::where('merchant_id', $value->merchant_id)
                    ->update([
                        'manajer_id' => $request->manajer_id,
                    ]);
            }
        }

        Alert::toast('Data Sales Berhasil Di Edit', 'success');

        return redirect('/admin/sales/table');
    }

    public function destroy(User $user)
    {
        User::destroy($user->id);

        Alert::toast('Data Sales Berhasil Di Hapus', 'success');

        return redirect('/admin/sales/table');
    }

    public function exportExcel()
    {
        $user = User::with(['manajerHasSales'])->where('role_id', 3)->latest()->get();

        return view('admin.sales.exportExcel', compact('user'));
    }

    public function exportPdf()
    {
        $user = User::with(['manajerHasSales'])->where('role_id', 3)->latest()->get();

        return view('admin.sales.exportPdf', compact('user'));
    }

    public function trash()
    {
        return view('admin.sales.trash');
    }

    public function trashData()
    {
        $sales = User::onlyTrashed()->with(['manajerHasSales', 'manajerHasSales.userManajer'])->where('role_id', 3)->latest()->get();

        return DataTables::of($sales)
            ->addColumn('actions', function ($data) {
                return '
            <a href="admin/sales/restore/' . $data->id . '" class="d-inline">
                <button class="btn btn-success btn-sm">Pulihkan</button>
            </a>
            <form action="/admin/sales/delete/' . $data->id . '" method="post" class="d-inline">
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
            $sales = User::onlyTrashed()->with(['manajerHasSales'])->where('role_id', 3)
                ->where('id', $id)->restore();

            if ($sales != null) {
                Alert::toast('Data Sales Berhasil Di Pulihkan', 'success');
            }
        } else {
            //restore semua data
            $sales = User::onlyTrashed()->with(['manajerHasSales'])->where('role_id', 3)->restore();
            if ($sales != null) {
                Alert::toast('Semua Data Sales Berhasil Di Pulihkan', 'success');
            }
        }

        return redirect('/admin/sales/trash');
    }

    public function delete($id = null)
    {
        if ($id != null) {
            //delete user berdasarkan id
            $sales = User::onlyTrashed()->with(['manajerHasSales'])->where('role_id', 3)
                ->where('id', $id)->forceDelete();

            if ($sales != null) {
                Alert::toast('Data Sales Berhasil Di Hapus Permanen', 'success');
            }
        } else {
            //delete semua data
            $sales = User::onlyTrashed()->with(['manajerHasSales'])->where('role_id', 3)->forceDelete();
            if ($sales != null) {
                Alert::toast('Semua Data Sales Berhasil Di Hapus Permanen', 'success');
            }
        }

        return redirect('/admin/sales/trash');
    }

    public function trashPdf()
    {
        $sales = User::onlyTrashed()->with(['manajerHasSales'])->where('role_id', 3)->latest()->get();

        return view('admin.sales.trashPdf', compact('sales'));
    }

    public function trashExcel()
    {
        $sales = User::onlyTrashed()->with(['manajerHasSales'])->where('role_id', 3)->latest()->get();

        return view('admin.sales.trashExcel', compact('sales'));
    }
}
