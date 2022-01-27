<?php

namespace App\Utilities;

use App\Models\Aktivitas;
use Illuminate\Database\Eloquent\Builder;
use App\Models\LogAktivitas;
use App\Models\ManajerHasSales;
use App\Models\Status;
use Request;

class Helpers
{
    //ASIDE MENU
    public static function requestAside($master, $path, $segment = 2)
    {
        if ($master == 'admin' && $segment == 2) {
            if (Request::segment($segment) == $path) {
                return 'menu-item-active';
            }
        } else  if ($master == 'manajer') {
            if (Request::segment($segment) == $path) {
                return 'menu-item-active';
            }
        } else if ($master == 'sales' && $segment == 2) {
            if (Request::segment($segment) == $path) {
                return 'menu-item-active';
            }
        } else if ($master == 'sales') {
            if (Request::segment($segment) == $path) {
                return 'menu-item-active';
            }
        }
    }

    // FORMAT CURRETCY (NOMINAL)
    public static function formatCurrency($number = 0, $unit = '', $isSuffix = false, $decimal = 0)
    {
        if ($isSuffix) {
            return number_format($number, $decimal, ',', '.') . ' ' . $unit;
        }
        return $unit . ' ' . number_format($number, $decimal, ',', '.');
    }

    // WARNA STATUS
    public static function statusColour($text)
    {
        $colour = 'primary';
        if ($text == 'Prospek') {
            $colour = 'danger';
        } elseif ($text == 'Demo/Presentasi') {
            $colour = 'warning';
        } elseif ($text == 'Closing Paid') {
            $colour = 'success';
        } elseif ($text == 'Maintenance') {
            $colour = 'info';
        }
        return '<span class="label font-weight-bold label-lg label-' . $colour . ' label-inline">
                ' . $text . '
            </span>';
    }

    //===========LAPORAN============
    //COUNT MERCHANT BERDASARKAN SALES 
    public static function countMerchantBySalesManajer($manajer_id, $sales_id)
    {
        $aktivitas = Aktivitas::with(['merchant'])->with(['logAktivitas', 'logAktivitas.status'])
            ->with(['manajerHasMerchant', 'manajerHasMerchant.userManajer'])
            ->with(['salesHasMerchant', 'salesHasMerchant.userSales'])
            ->where('manajer_id', $manajer_id)->where('sales_id', $sales_id)
            ->latest()->get()->count();

        return $aktivitas;
    }

    //GET SALES_ID XX
    public static function link_merchant($sales_id, $merchant_id)
    {
        $merchant = collect(Aktivitas::with(['logAktivitas', 'logAktivitas.status'])
            ->with(['merchant'])
            ->with(['manajerHasMerchant', 'manajerHasMerchant.userManajer'])
            ->with(['salesHasMerchant', 'salesHasMerchant.userSales'])
            ->where('sales_id', $sales_id)
            ->where('merchant_id', $merchant_id)
            ->latest()->get()->toArray());

        //filtering berdasarkan sales_id (rewrite aja, takut ga kebaca)
        $merchant = $merchant->where('sales_id', $sales_id)->values();

        return $merchant[0]['sales_id'] ?? '';
    }


    // COUNT STATUS BERDASARKAN SALES
    public static function statusCount($sales_id, $status_id)
    {
        //get data nya, status berdasarkan sales > di distinct(groupBy) berdasarkan aktivitas_id
        $aktivitas = collect(Aktivitas::with(['logAktivitas', 'logAktivitas.status'])
            ->with(['merchant'])
            ->with(['manajerHasMerchant', 'manajerHasMerchant.userManajer'])
            ->with(['salesHasMerchant', 'salesHasMerchant.userSales'])
            ->where('sales_id', $sales_id)
            ->whereHas('logAktivitas', function (Builder $q) use ($status_id) { //gakebaca
                $q->where('status_id', $status_id)->distinct('aktivitas_id');
            })
            ->latest()->get()->toArray());

        //filtering dr status_id (ngulang statement yg diatas, kemungkinan ga kebaca)
        $aktivitas = $aktivitas->where('status_id', $status_id)->values();

        //count statusnya
        return count($aktivitas);
    }

    //GET SALES_ID dr status XX
    public static function link_status($sales_id, $status_id)
    {
        $aktivitas = collect(Aktivitas::with(['logAktivitas', 'logAktivitas.status'])
            ->with(['merchant'])
            ->with(['manajerHasMerchant', 'manajerHasMerchant.userManajer'])
            ->with(['salesHasMerchant', 'salesHasMerchant.userSales'])
            ->where('sales_id', $sales_id)
            ->whereHas('logAktivitas', function (Builder $q) use ($status_id) {
                $q->where('status_id', $status_id)->distinct('aktivitas_id');
            })
            // ->where('id', $aktivitas_id)
            ->latest()->get()->toArray());

        $aktivitas = $aktivitas->where('status_id', $status_id)->values();

        return $aktivitas[0]['sales_id'] ?? '';
    }


    //==================DASHBOARD======================
    //COUNT DAASHBOARD
    public static function statusCountDash($logAktivitas, $status_id)
    { //$logAktivitas ad valriabel $aktivitas dr dashboard (diganti namanya)

        $angka = 0;
        // dd($logAktivitas[2]->aktivitas->sales_id);
        foreach ($logAktivitas as $value) {
            $aktivitas = collect(Aktivitas::with(['logAktivitas', 'logAktivitas.status'])
                ->with(['merchant'])
                ->with(['manajerHasMerchant', 'manajerHasMerchant.userManajer'])
                ->with(['salesHasMerchant', 'salesHasMerchant.userSales'])
                ->where('merchant_id', $value->aktivitas->merchant->id)
                ->whereHas('logAktivitas', function (Builder $q) use ($status_id) {
                    $q->where('status_id', $status_id)->distinct('aktivitas_id');
                })
                ->latest()->get()->toArray());

            $aktivitas = $aktivitas->where('status_id', $status_id)->values();
            $angka = $angka + count($aktivitas);
        }
        // dd($angka);
        return $angka;
    }

    //GET STATUS_ID XX
    public static function dashboard_id($merchant_id, $status_id)
    {
        $aktivitas = collect(Aktivitas::with(['logAktivitas', 'logAktivitas.status'])
            ->with(['merchant'])
            ->with(['manajerHasMerchant', 'manajerHasMerchant.userManajer'])
            ->with(['salesHasMerchant', 'salesHasMerchant.userSales'])
            ->where('merchant_id', $merchant_id)
            ->whereHas('logAktivitas', function (Builder $q) use ($status_id) {
                $q->where('status_id', $status_id)->distinct('aktivitas_id');
            })
            ->latest()->get()->toArray());

        $aktivitas = $aktivitas->where('log_aktivitas.status_id', $status_id)->values();

        return $aktivitas[0]['log_aktivitas.status_id'] ?? '';
    }
}
