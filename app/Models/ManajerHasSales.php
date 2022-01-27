<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManajerHasSales extends Model
{
    use HasFactory;

    protected $table = 'manajer_has_sales';

    protected $fillable = [
        'manajer_id',
        'sales_id',
    ];

    public function userManajer()
    {
        return $this->belongsTo(User::class, 'manajer_id', 'id');
    }

    public function userSales()
    {
        return $this->belongsTo(User::class, 'sales_id', 'id');
    }

    public function userMany()
    {
        return $this->hasMany('App\Models\User');
    }

    public function aktivitas()
    {
        return $this->belongsTo(Aktivitas::class, 'sales_id', 'sales_id');
    }

    public function aktivitasM()
    {
        return $this->belongsTo(Aktivitas::class, 'manajer_id', 'manajer_id');
    }
}
