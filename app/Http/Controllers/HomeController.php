<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\ManajerHasMerchant;
use App\Models\ManajerHasSales;
use Illuminate\Http\Request;
use App\Models\Merchant;
use App\Models\Aktivitas;
use App\Models\SalesHasMerchant;
use App\Models\User;
use App\Models\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use App\Utilities\Helpers;
use DataTables;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('auth.login');
    }

    //ADMIN
    public function admin()
    {
        $manajer = User::where('role_id', 2)->count();
        $sales = User::where('role_id', 3)->count();
        $merchant_all = Merchant::with('aktivitas.status')->get()->toArray();
        $merchant = count($merchant_all);

        // untuk menangkap ke maps
        $merchant_location = collect($merchant_all)->map(function ($value) {
            $data[] = $value['nama_merchant'];
            $data[] = $value['latitude'];
            $data[] = $value['longitude'];
            if ($value['aktivitas']) {
                if ($value['aktivitas']['status']) {
                    $data[] = $value['aktivitas']['status']['nama_status'];
                } else {
                    $data[] = 'Pending';
                }
            } else {
                $data[] = '';
            }
            return $data;
        })->toArray();

        //terhubung ke dashboard > statusCountDash (helpers)
        $aktivitas = LogAktivitas::with(['status'])
            ->with(['aktivitas', 'aktivitas.merchant'])
            ->with(['aktivitas.merchant.salesHasMerchant', 'aktivitas.merchant.salesHasMerchant.userSales'])
            ->with(['aktivitas.merchant.manajerHasMerchant', 'aktivitas.merchant.manajerHasMerchant.userManajer'])
            ->latest()->get();

        $aktivitas = $aktivitas->groupBy('aktivitas_id')->map(function ($data) {
            return $data->first();
        })->values();

        return view('admin.dashboard', compact('merchant', 'manajer', 'sales', 'aktivitas', 'merchant_location'));
    }

    public function status_admin($aktivitas)
    {
        return view('admin.aktivitas.status', ['status_id' => $aktivitas]);
    }

    public function indexDataAdmin($status_id)
    {
        $aktivitas = LogAktivitas::with(['status'])
            ->with(['aktivitas', 'aktivitas.merchant'])
            ->with(['aktivitas.merchant.salesHasMerchant', 'aktivitas.merchant.salesHasMerchant.userSales'])
            ->with(['aktivitas.merchant.manajerHasMerchant', 'aktivitas.merchant.manajerHasMerchant.userManajer'])
            ->whereHas('aktivitas', function (Builder $q) use ($status_id) {
                $q->where('status_id', $status_id)->distinct('id');
            })
            ->where('status_id', $status_id)
            ->latest()->get();

        $aktivitas = $aktivitas->groupBy('aktivitas_id')->map(function ($data) {
            return $data->first();
        })->values();
        // return $aktivitas;

        return DataTables::of($aktivitas)
            ->editColumn('tanggal', function ($data) {
                return \Carbon\Carbon::parse($data->tanggal)->format('d-m-Y');
            })
            ->editColumn('nominal', function ($data) {
                return Helpers::formatCurrency($data->nominal, 'Rp');
            })
            ->editColumn('status', function ($data) {
                return Helpers::statusColour($data->status->nama_status ?? '');
            })
            ->rawColumns(['status'])
            ->toJson();
    }

    //MANAJER
    public function manajer()
    {
        $sales = ManajerHasSales::where('manajer_id', auth()->user()->id)->count();

        $merchant_m = Merchant::with('aktivitas.status')->with(['manajerHasMerchant'])
            ->whereHas('manajerHasMerchant', function (Builder $q) {
                $q->where('manajer_id', auth()->user()->id);
            })
            ->get()->toArray();
        $merchant = count($merchant_m);

        // untuk menangkap ke maps
        $merchant_location = collect($merchant_m)->map(function ($value) {
            $data[] = $value['nama_merchant'];
            $data[] = $value['latitude'];
            $data[] = $value['longitude'];
            if ($value['aktivitas']) {
                if ($value['aktivitas']['status']) {
                    $data[] = $value['aktivitas']['status']['nama_status'];
                } else {
                    $data[] = 'Pending';
                }
            } else {
                $data[] = '';
            }
            return $data;
        })->toArray();

        //terhubung ke dashboard > statusCountDash (helpers)
        $aktivitas = LogAktivitas::with(['status'])
            ->with(['aktivitas', 'aktivitas.merchant'])
            ->with(['aktivitas.merchant.salesHasMerchant', 'aktivitas.merchant.salesHasMerchant.userSales'])
            ->with(['aktivitas.merchant.manajerHasMerchant', 'aktivitas.merchant.manajerHasMerchant.userManajer'])
            ->whereHas('aktivitas', function (Builder $q) {
                $q->where('manajer_id', auth()->user()->id);
            })
            ->latest()->get();

        $aktivitas = $aktivitas->groupBy('aktivitas_id')->map(function ($data) {
            return $data->first();
        })->values();

        return view('manajer.dashboard', compact('sales', 'merchant', 'aktivitas', 'merchant_location'));
    }

    public function status_manajer($aktivitas)
    {
        return view('manajer.aktivitas.status', ['status_id' => $aktivitas]);
    }

    public function indexDataManajer($status_id)
    {
        $aktivitas = LogAktivitas::with(['status'])
            ->with(['aktivitas', 'aktivitas.merchant'])
            ->with(['aktivitas.merchant.salesHasMerchant', 'aktivitas.merchant.salesHasMerchant.userSales'])
            ->with(['aktivitas.merchant.manajerHasMerchant', 'aktivitas.merchant.manajerHasMerchant.userManajer'])
            ->whereHas('aktivitas', function (Builder $q) use ($status_id) {
                $q->where('manajer_id', auth()->user()->id)->where('status_id', $status_id)->distinct('id');
            })
            ->where('status_id', $status_id)
            ->latest()->get();

        $aktivitas = $aktivitas->groupBy('aktivitas_id')->map(function ($data) {
            return $data->first();
        })->values();

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
            ->rawColumns(['status'])
            ->toJson();
    }

    //===============================================
    //SALES
    public function sales()
    {
        // $merchant = SalesHasMerchant::where('sales_id', auth()->user()->id)->count();
        $merchant_s = Merchant::with('aktivitas.status')->with(['salesHasMerchant'])
            ->whereHas('salesHasMerchant', function (Builder $q) {
                $q->where('sales_id', auth()->user()->id);
            })
            ->get()->toArray();
        $merchant = count($merchant_s);

        // untuk menangkap ke maps
        $merchant_location = collect($merchant_s)->map(function ($value) {
            $data[] = $value['nama_merchant'];
            $data[] = $value['latitude'];
            $data[] = $value['longitude'];
            if ($value['aktivitas']) {
                if ($value['aktivitas']['status']) {
                    $data[] = $value['aktivitas']['status']['nama_status'];
                } else {
                    $data[] = 'Pending';
                }
            } else {
                $data[] = '';
            }
            return $data;
        })->toArray();


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

        return view('sales.dashboard', compact('merchant', 'aktivitas', 'merchant_location'));
    }

    public function status_sales($aktivitas)
    {
        return view('sales.aktivitas.status', ['status_id' => $aktivitas]);
    }

    public function indexDataSales($status_id)
    {
        $aktivitas = LogAktivitas::with(['status'])
            ->with(['aktivitas', 'aktivitas.merchant'])
            ->with(['aktivitas.merchant.salesHasMerchant', 'aktivitas.merchant.salesHasMerchant.userSales'])
            ->with(['aktivitas.merchant.manajerHasMerchant', 'aktivitas.merchant.manajerHasMerchant.userManajer'])
            ->whereHas('aktivitas', function (Builder $q) use ($status_id) {
                $q->where('sales_id', auth()->user()->id)->where('status_id', $status_id)->distinct('id');
            })
            ->where('status_id', $status_id)
            ->latest()->get();

        $aktivitas = $aktivitas->groupBy('aktivitas_id')->map(function ($data) {
            return $data->first();
        })->values();

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
            ->rawColumns(['status'])
            ->toJson();
    }
}
