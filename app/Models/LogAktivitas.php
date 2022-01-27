<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    use HasFactory;

    protected $table = 'log_aktivitas';

    protected $fillable = [
        // 'tanggal',
        'aktivitas_id',
        'status_id',
        'keterangan',
        'nominal',
        'foto',
        'tanggal',
    ];

    public function aktivitas()
    {
        return $this->belongsTo(Aktivitas::class, 'aktivitas_id', 'id');
    }

    public function aktivitasMany()
    {
        return $this->hasMany('App\Models\Aktivitas');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }

    public function statusMany()
    {
        return $this->hasMany('App\Models\Status');
    }
}
