<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use App\Models\ManajerHasSales;
use DataTables;

class MSalesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        return view('manajer.sales.table');
    }

    public function indexData()
    {
        $sales = User::with(['manajerHasSales.userManajer'])
            ->whereHas('manajerHasSales', function (Builder $q) {
                $q->where('manajer_id', auth()->user()->id);
            })
            ->where('role_id', 3)->latest()->get();

        return DataTables::of($sales)
            ->addColumn('actions', function ($data) {
                return '
            <a href="/manajer/sales/' . $data->id . '/edit" class="d-inline">
                 <button class="btn btn-warning btn-sm"> <i class="far fa-edit text-white"></i></button>
            </a>
             <form action="/manajer/sales/' . $data->id . '" method="post" class="d-inline">
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
        return view('manajer.sales.add');
    }

    public function store(Request $request)
    {
        $request->validate([
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
            'manajer_id' => $request->user()->id,
            'sales_id' => $sales_id,
        ]);

        Alert::toast('Data Sales Berhasil Di Tambahkan', 'success');

        return redirect('/manajer/sales/table');
    }

    public function resetPassword(User $user)
    {
        User::where('id', $user->id)
            ->update([
                'password' => bcrypt('sales'),
            ]);

        Alert::toast('Password Sales Berhasil Di Ubah', 'success');

        return redirect('/manajer/sales/' . $user->id . '/edit');
    }
    public function edit(User $user)
    {
        $manajer = User::with(['manajerHasSales.userManajer'])->get();

        return view('manajer.sales.edit', compact('user'));
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
            foreach ($nomor as $no) {
                if ($no->nomor_telepon == $request->nomor_telepon) {
                    return redirect('manajer/sales/' . $user->id . '/edit')->with('nomor_telepon', 'The nomor telepon has already been taken.');
                }
            }
        }

        User::where('id', $user->id)
            ->update([
                'nama_user' => $request->nama_user,
                'nomor_telepon' => $request->nomor_telepon,
            ]);

        Alert::toast('Data Sales Berhasil Di Edit', 'success');

        return redirect('/manajer/sales/table');
    }

    public function destroy(User $user)
    {
        User::destroy($user->id);

        Alert::toast('Data Sales Berhasil Di Hapus', 'success');

        return redirect('/manajer/sales/table');
    }

    public function exportExcel()
    {
        $user = User::with(['manajerHasSales.userManajer'])
            ->whereHas('manajerHasSales', function (Builder $q) {
                $q->where('manajer_id', auth()->user()->id);
            })->where('role_id', 3)->latest()->get();

        return view('manajer.sales.exportExcel', compact('user'));
    }

    public function exportPdf()
    {
        $user = User::with(['manajerHasSales.userManajer'])
            ->whereHas('manajerHasSales', function (Builder $q) {
                $q->where('manajer_id', auth()->user()->id);
            })->where('role_id', 3)->latest()->get();

        return view('manajer.sales.exportPdf', compact('user'));
    }

    public function trash()
    {
        return view('manajer.sales.trash');
    }

    public function trashData()
    {
        $sales = User::onlyTrashed()->with(['manajerHasSales'])
            ->whereHas('manajerHasSales', function (Builder $q) {
                $q->where('manajer_id', auth()->user()->id);
            })->where('role_id', 3)->latest()->get();

        return DataTables::of($sales)
            ->addColumn('actions', function ($data) {
                return '
            <a href="manajer/sales/restore/' . $data->id . '" class="d-inline">
                <button class="btn btn-success btn-sm">Pulihkan
                </button>
            </a>
            <form action="/manajer/sales/delete/' . $data->id . '" method="post" class="d-inline">
                <input type="hidden" name="_method" value="delete">
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
            $sales = User::onlyTrashed()->with(['manajerHasSales'])
                ->whereHas('manajerHasSales', function (Builder $q) {
                    $q->where('manajer_id', auth()->user()->id);
                })->where('role_id', 3)->where('id', $id)->restore();

            if ($sales != null) {
                Alert::toast('Data Sales Berhasil Di Pulihkan', 'success');
            }
        } else {
            //restore semua data
            $sales = User::onlyTrashed()->with(['manajerHasSales'])
                ->whereHas('manajerHasSales', function (Builder $q) {
                    $q->where('manajer_id', auth()->user()->id);
                })->where('role_id', 3)->restore();

            if ($sales != null) {
                Alert::toast('Semua Data Sales Berhasil Di Pulihkan', 'success');
            }
        }

        return redirect('/manajer/sales/trash');
    }

    public function delete($id = null)
    {
        if ($id != null) {

            //delete user berdasarkan id
            $sales = User::onlyTrashed()->with(['manajerHasSales'])
                ->whereHas('manajerHasSales', function (Builder $q) {
                    $q->where('manajer_id', auth()->user()->id);
                })->where('role_id', 3)->where('id', $id)->forceDelete();

            if ($sales != null) {
                Alert::toast('Data Sales Berhasil Di Hapus Permanen', 'success');
            }
        } else {
            //delete semua data
            $sales = User::onlyTrashed()->with(['manajerHasSales'])
                ->whereHas('manajerHasSales', function (Builder $q) {
                    $q->where('manajer_id', auth()->user()->id);
                })->where('role_id', 3)->forceDelete();

            if ($sales != null) {
                Alert::toast('Semua Data Sales Berhasil Di Hapus Permanen', 'success');
            }
        }

        return redirect('/manajer/sales/trash');
    }

    public function trashPdf()
    {
        $sales = User::onlyTrashed()->with(['manajerHasSales'])
            ->whereHas('manajerHasSales', function (Builder $q) {
                $q->where('manajer_id', auth()->user()->id);
            })->where('role_id', 3)->latest()->get();

        return view('manajer.sales.trashPdf', compact('sales'));
    }

    public function trashExcel()
    {
        $sales = User::onlyTrashed()->with(['manajerHasSales'])
            ->whereHas('manajerHasSales', function (Builder $q) {
                $q->where('manajer_id', auth()->user()->id);
            })->where('role_id', 3)->latest()->get();

        return view('manajer.sales.trashExcel', compact('sales'));
    }
}
