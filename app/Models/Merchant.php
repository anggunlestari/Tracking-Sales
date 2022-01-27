<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    use HasFactory;

    protected $table = 'merchant';

    protected $fillable = [
        'nama_merchant',
        'kategori_id',
        'nama_pemilik',
        'nomor_telepon',
        'alamat',
        'province_id',
        'city_id',
        'district_id',
        'latitude',
        'longitude',
        'foto',
    ];

    //baru

    public function aktivitas()
    {
        return $this->belongsTo(Aktivitas::class, 'id', 'merchant_id');
    }

    public function manajerHasSales()
    {
        return $this->belongsTo(ManajerHasSales::class, 'id', 'manajer_id');
    }


    public function salesHasManajer()
    {
        return $this->belongsTo(ManajerHasSales::class, 'id', 'sales_id');
    }




    public function kategori() //nama fungsi tabel(primary key)
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id'); //id baru bikin FK
    }

    public function kategoriMany()
    {
        return $this->hasMany('App\Models\Kategori');
    }

    public function salesHasMerchant()
    {
        return $this->belongsTo(SalesHasMerchant::class, 'id', 'merchant_id');
    }

    public function manajerHasMerchant()
    {
        return $this->belongsTo(ManajerHasMerchant::class, 'id', 'merchant_id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }

    public function provinceMany()
    {
        return $this->hasMany('App\Models\Province');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function cityMany()
    {
        return $this->hasMany('App\Models\City');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }

    public function districtMany()
    {
        return $this->hasMany('App\Models\District');
    }
}
