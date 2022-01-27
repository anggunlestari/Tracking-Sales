<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $table = 'indonesia_cities';

    protected $fillable = [
        'province_id',
        'name',
        'meta',
    ];

    public function arealokasi()
    {
        return $this->belongsTo(AreaLokasi::class);
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'id', 'city_id');
    }


    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }

    public function provinceMany()
    {
        return $this->hasMany('App\Models\Province');
    }
}
