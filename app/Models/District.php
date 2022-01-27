<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $table = 'indonesia_districts';

    protected $fillable = [
        'city_id',
        'name',
        'meta',
    ];

    public function arealokasi()
    {
        return $this->belongsTo(AreaLokasi::class, 'id', 'district_id');
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function cityMany()
    {
        return $this->hasMany('App\Models\City');
    }
}
