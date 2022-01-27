<?php

namespace App\Models;

use CreateProvincesTable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaLokasi extends Model
{
    use HasFactory;

    protected $table = 'area_lokasi';

    protected $fillable = [
        'nama_area',
        'province_id',
        'city_id',
        'district_id',
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
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
