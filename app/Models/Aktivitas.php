<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aktivitas extends Model
{
    use HasFactory;

    protected $table = 'aktivitas';

    protected $fillable = [
        'manajer_id',
        'sales_id',
        'merchant_id',
        'status_id',
    ];

    //function baru

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }

    public function LogAktivitas()
    {
        return $this->belongsTo(LogAktivitas::class, 'id', 'aktivitas_id');
    }

    public function manajer()
    {
        return $this->belongsTo(User::class, 'manajer_id', 'id');
    }

    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id', 'id');
    }

    public function user()
    {
        return $this->hasMany('App\Models\User');
    }


    public function manajerHasMerchant()
    {
        return $this->belongsTo(ManajerHasMerchant::class, 'manajer_id', 'manajer_id');
    }

    public function salesHasMerchant()
    {
        return $this->belongsTo(SalesHasMerchant::class, 'sales_id', 'sales_id');
    }

    public function manajerMerchantMany()
    {
        return $this->hasMany('App\Models\ManajerHasMerchant');
    }

    public function salesMerchantMany()
    {
        return $this->hasMany('App\Models\SalesHasMerchant');
    }




    public function manajerHasSales()
    {
        return $this->belongsTo(ManajerHasSales::class, 'manajer_id', 'manajer_id');
    }

    public function salesHasManajer()
    {
        return $this->belongsTo(ManajerHasSales::class, 'sales_id', 'sales_id');
    }

    public function manajerSalesMany()
    {
        return $this->hasMany('App\Models\ManajerHasSales');
    }

    public function salesManajerMany()
    {
        return $this->hasMany('App\Models\ManajerHasSales');
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id', 'id');
    }
    // id merchant_id

    public function merchantMany()
    {
        return $this->hasMany('App\Models\Merchant');
    }
}
