<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    use HasFactory;

    protected $fillable = ['pengajuan_id', 'persyaratan_id', 'file', 'text'];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function persyaratan()
    {
        return $this->belongsTo(Persyaratan::class);
    }
}