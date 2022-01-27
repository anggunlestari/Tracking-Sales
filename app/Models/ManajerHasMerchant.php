<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManajerHasMerchant extends Model
{
    use HasFactory;

    protected $table = 'manajer_has_merchant';

    protected $fillable = [
        'manajer_id',
        'merchant_id',
    ];

    //gimana dia bedain user_idnya manajer dan sales???
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

    public function userManajer()
    {
        return $this->belongsTo(User::class, 'manajer_id', 'id');
    }


    public function aktivitasMany()
    {
        return $this->hasMany('App\Models\Aktivitas');
    }
}
