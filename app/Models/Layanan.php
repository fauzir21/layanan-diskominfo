<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'slug', 'deskripsi', 'kategori', 'status'];

    public function persyaratans()
    {
        return $this->belongsToMany(Persyaratan::class, 'layanan_persyaratan')
            ->withPivot('urutan')
            ->orderBy('layanan_persyaratan.urutan');
    }
}