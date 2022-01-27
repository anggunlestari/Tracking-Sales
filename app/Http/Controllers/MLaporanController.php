<?php

namespace App\Http\Controllers;

use App\Models\Aktivitas;
use App\Models\LogAktivitas;
use Illuminate\Database\Eloquent\Builder;
use App\Utilities\Helpers;
use DataTables;

class MLaporanController extends Controller
{
    public function index()
    {
        return view('manajer.laporan.table');
    }

    public function indexData()
    {
        $aktivitas = Aktivitas::with(['merchant'])->with(['logAktivitas', 'logAktivitas.status'])
            ->with(['manajerHasMerchant', 'manajerHasMerchant.userManajer'])
            ->with(['salesHasMerchant', 'salesHasMerchant.userSales'])
            ->where('manajer_id', auth()->user()->id)
            ->latest()->get();

        $aktivitas = $aktivitas->groupBy('sales_id')->map(function ($data) {
            return $data->first();
        })->values();

        return DataTables::of($aktivitas)
            ->addColumn('tmerchant', function ($data) {
                if (Helpers::link_merchant($data['sales_id'], $data['merchant_id']) != '') {
                    $linkMerchant = 'manajer/aktivitas/' . Helpers::link_merchant($data['sales_id'], $data['merchant_id']) . '/merchant';
                } else {
                    $linkMerchant = 'manajer/laporan/table';
                }
                return ' <a href="' . $linkMerchant . '"  class="btn btn-light-light btn-sm text-dark"> ' . Helpers::countMerchantBySalesManajer($data['manajer_id'], $data['sales_id']) ?? '' . '
                </a>
                ';
            })

            ->addColumn('tprospek', function ($data) {
                if (Helpers::link_status($data['sales_id'], 1) != '') {
                    $linkProspek = 'manajer/aktivitas/' . Helpers::link_status($data['sales_id'], 1) . '/status/' . 1;
                } else {
                    $linkProspek = 'manajer/laporan/table';
                }
                return '
                <a href=" ' . $linkProspek . ' " class="btn btn-light-light btn-sm text-dark">' . Helpers::statusCount($data['sales_id'], 1) . '</a>
                ';
            })

            ->addColumn('tdemo', function ($data) {
                if (Helpers::link_status($data['sales_id'], 2) != '') {
                    $linkDemo = 'manajer/aktivitas/' . Helpers::link_status($data['sales_id'], 2) . '/status/' . 2;
                } else {
                    $linkDemo = 'manajer/laporan/table';
                }
                return '
                <a href=" ' . $linkDemo . ' " class="btn btn-light-light btn-sm text-dark">' . Helpers::statusCount($data['sales_id'], 2) . '</a>
                ';
            })

            ->addColumn('tclosing', function ($data) {
                if (Helpers::link_status($data['sales_id'], 3) != '') {
                    $linkClosing = 'manajer/aktivitas/' . Helpers::link_status($data['sales_id'], 3) . '/status/' . 3;
                } else {
                    $linkClosing = 'manajer/laporan/table';
                }
                return '
                 <a href=" ' . $linkClosing . ' " class="btn btn-light-light btn-sm text-dark">' . Helpers::statusCount($data['sales_id'], 3) . '</a>
                 ';
            })

            ->addColumn('tpending', function ($data) {
                if (Helpers::link_status($data['sales_id'], 4) != '') {
                    $linkPending = 'manajer/aktivitas/' . Helpers::link_status($data['sales_id'], 4) . '/status/' . 4;
                } else {
                    $linkPending = 'manajer/laporan/table';
                }
                return '
                <a href=" ' . $linkPending . ' " class="btn btn-light-light btn-sm text-dark">' . Helpers::statusCount($data['sales_id'], 4) . '</a>
                ';
            })

            ->addColumn('tmaintenance', function ($data) {
                if (Helpers::link_status($data['sales_id'], 5) != '') {
                    $linkMaintenance = 'manajer/aktivitas/' . Helpers::link_status($data['sales_id'], 5) . '/status/' . 5;
                } else {
                    $linkMaintenance = 'manajer/laporan/table';
                }
                return '
                <a href=" ' . $linkMaintenance . ' " class="btn btn-light-light btn-sm text-dark">' . Helpers::statusCount($data['sales_id'], 5) . '</a>
                ';
            })
            ->rawColumns(['tmerchant', 'tprospek', 'tdemo', 'tclosing', 'tpending', 'tmaintenance'])
            ->toJson();
    }

    public function link_merchant($aktivitas)
    {
        return view('manajer.laporan.aktivitas_merchant', ['sales_id' => $aktivitas]);
    }

    public function indexDataMerchant($sales_id)
    {
        $aktivitas = LogAktivitas::with(['status'])
            ->with(['aktivitas', 'aktivitas.merchant'])
            ->with(['aktivitas.merchant.salesHasMerchant', 'aktivitas.merchant.salesHasMerchant.userSales'])
            ->with(['aktivitas.merchant.manajerHasMerchant', 'aktivitas.merchant.manajerHasMerchant.userManajer'])
            ->whereHas('aktivitas', function (Builder $q) use ($sales_id) {
                $q->where('sales_id', $sales_id);
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
            ->addColumn('actions', function ($data) {
                return '
            <a href="/manajer/aktivitas/' . $data->aktivitas_id . '/edit" class="d-inline">
                <button class="btn btn-warning btn-sm"> <i class="far fa-edit text-white"></i></button>
            </a>
            <form action="/manajer/aktivitas/' . $data->aktivitas_id . '" method="post" class="d-inline">
                <input type="hidden" name="_method" value="delete">      
                ' . @csrf_field() . '             
                <button type="submit" class="hapus btn btn-danger btn-sm"><i class="far fa-trash-alt text-white"></i></button>
            </form> ';
            })
            ->rawColumns(['status', 'actions'])
            ->toJson();
    }

    public function link_status($aktivitas, $status)
    {
        return view('manajer.laporan.aktivitas_status',  ['sales_id' => $aktivitas, 'status_id' => $status]);
    }

    public function indexDataStatus($sales_id, $status_id)
    {
        $aktivitas = LogAktivitas::with(['status'])
            ->with(['aktivitas', 'aktivitas.merchant'])
            ->with(['aktivitas.merchant.salesHasMerchant', 'aktivitas.merchant.salesHasMerchant.userSales'])
            ->with(['aktivitas.merchant.manajerHasMerchant', 'aktivitas.merchant.manajerHasMerchant.userManajer'])
            ->whereHas('aktivitas', function (Builder $q) use ($sales_id, $status_id) {
                $q->where('sales_id', $sales_id)->where('status_id', $status_id);
            })
            ->where('status_id', $status_id)
            ->latest()->get();

        $aktivitas = $aktivitas->groupBy('aktivitas_id')->map(function ($data) {
            return $data->first();
        })->values();

        $status = $aktivitas->where('status_id', $status_id)->first();

        return DataTables::of($aktivitas, $status)
            ->editColumn('tanggal', function ($data) {
                return \Carbon\Carbon::parse($data->tanggal)->format('d-m-Y');
            })
            ->editColumn('status', function ($data) {
                return Helpers::statusColour($data->status->nama_status ?? '');
            })
            ->editColumn('nominal', function ($data) {
                return Helpers::formatCurrency($data->nominal, 'Rp' ?? '');
            })
            ->addColumn('actions', function ($data) {
                return '
            <a href="/manajer/aktivitas/' . $data->aktivitas_id . '/edit" class="d-inline">
                <button class="btn btn-warning btn-sm"> <i class="far fa-edit text-white"></i></button>
            </a>
            <form action="/manajer/aktivitas/' . $data->aktivitas_id . '" method="post" class="d-inline">
                <input type="hidden" name="_method" value="delete">      
                ' . @csrf_field() . '             
                <button type="submit" class="hapus btn btn-danger btn-sm"><i class="far fa-trash-alt text-white"></i></button>
            </form> ';
            })
            ->rawColumns(['status', 'actions'])
            ->toJson();
    }


    public function exportExcel()
    {
        $aktivitas = Aktivitas::with(['merchant'])->with(['logAktivitas', 'logAktivitas.status'])
            ->with(['manajerHasMerchant', 'manajerHasMerchant.userManajer'])
            ->with(['salesHasMerchant', 'salesHasMerchant.userSales'])
            ->where('manajer_id', auth()->user()->id)
            ->latest()->get();

        $aktivitas = $aktivitas->groupBy('sales_id')->map(function ($data) {
            return $data->first();
        })->values();

        return view('manajer.laporan.exportExcel', compact('aktivitas'));
    }

    public function exportPdf()
    {
        $aktivitas = Aktivitas::with(['merchant'])->with(['logAktivitas', 'logAktivitas.status'])
            ->with(['manajerHasMerchant', 'manajerHasMerchant.userManajer'])
            ->with(['salesHasMerchant', 'salesHasMerchant.userSales'])
            ->where('manajer_id', auth()->user()->id)
            ->latest()->get();

        $aktivitas = $aktivitas->groupBy('sales_id')->map(function ($data) {
            return $data->first();
        })->values();

        return view('manajer.laporan.exportPdf', compact('aktivitas'));
    }
}
