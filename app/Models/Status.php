<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $table = 'status';

    protected $fillable = [
        'nama_status',
    ];

    public function logAktivitas()
    {
        return $this->belongsTo(LogAktivitas::class, 'id', 'status_id');
    }

    public function Aktivitas()
    {
        return $this->belongsTo(Aktivitas::class, 'id', 'status_id');
    }
}
