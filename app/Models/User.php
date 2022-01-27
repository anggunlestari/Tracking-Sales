<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use SoftDeletes;
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'role_id',
        'area_id',
        'nama_user',
        'nomor_telepon',
        'email',
        'password',
        'foto'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role() //nama fungsi tabel (primary key)
    {
        return $this->belongsTo(Role::class, 'role_id', 'id'); //id baru bikin FK
    }

    public function roleMany()
    {
        return $this->hasMany('App\Models\Role');
    }

    public function area_lokasi()
    {
        return $this->belongsTo(AreaLokasi::class, 'area_id', 'id');
    }

    public function areaOne()
    {
        return $this->hasOne('App\Models\AreaLokasi');
    }

    public function salesHasMerchant()
    {
        return $this->belongsTo(SalesHasMerchant::class, 'id', 'sales_id');
    }

    public function manajerHasMerchant()
    {
        return $this->belongsTo(ManajerHasMerchant::class);
    }

    public function manajerHasSales()
    {
        return $this->belongsTo(ManajerHasSales::class, 'id', 'sales_id');
    }

    // public function ManajerHasSales()
    // {
    //     return $this->hasMany('App\Models\ManajerHasSales');
    // }
}
