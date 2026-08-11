<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimKerja extends Model
{
    use HasFactory;

    protected $fillable = ['nama_tim'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'tim_kerja_user');
    }

    public function layanans()
    {
        return $this->hasMany(Layanan::class);
    }
}