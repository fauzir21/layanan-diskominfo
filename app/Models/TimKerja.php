<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimKerja extends Model
{
    use HasFactory;

    protected $table = 'tim_kerjas';

    protected $fillable = [
        'nama_tim',
    ];

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'tim_kerja_user',
            'tim_kerja_id',
            'user_id'
        );
    }

    public function layanans()
    {
        return $this->hasMany(Layanan::class);
    }

    public function riwayatDisposisis()
    {
        return $this->hasMany(
            RiwayatDisposisi::class,
            'tim_kerja_id'
        );
    }
}