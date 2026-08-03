<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persyaratan extends Model
{
    use HasFactory;

    protected $fillable = ['layanan_id', 'nama_syarat', 'urutan'];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }
}