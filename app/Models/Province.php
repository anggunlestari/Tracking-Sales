<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $table = 'indonesia_provinces';

    protected $fillable  = [
        'name',
        'meta',
    ];

    public function area_lokasi()
    {
        return $this->belongsTo(AreaLokasi::class, 'id', 'province_id');
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'id', 'province_id');
    }
}
