<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesHasMerchant extends Model
{
    use HasFactory;

    protected $table = 'sales_has_merchant';

    protected $fillable = [
        'sales_id',
        'merchant_id',
    ];

    public function user() //nama fungsi tabel(primary key)
    {
        return $this->belongsTo(User::class, 'user_id', 'id'); //id baru bikin FK
    }

    public function userMany()
    {
        return $this->hasMany('App\Models\User');
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id', 'id');
    }

    public function merchantMany()
    {
        return $this->hasMany('App\Models\Merchant');
    }

    public function userSales()
    {
        return $this->belongsTo(User::class, 'sales_id', 'id');
    }



    public function aktivitasMany()
    {
        return $this->hasMany('App\Models\Aktivitas');
    }

    //NYOBA2 di SAktivitasController
    // public function salesMerchant()
    // {
    //     return $this->belongsTo(Merchant::class, 'sales_id', 'id');
    // }
}
