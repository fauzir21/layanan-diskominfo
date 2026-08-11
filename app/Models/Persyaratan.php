<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persyaratan extends Model
{
    use HasFactory;

    protected $fillable = ['nama_syarat', 'tipe', 'wajib'];

    protected function casts(): array
    {
        return [
            'wajib' => 'boolean',
        ];
    }

    public function layanans()
    {
        return $this->belongsToMany(Layanan::class, 'layanan_persyaratan')
            ->withPivot('urutan');
    }
}