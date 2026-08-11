<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory;

    protected $fillable = [
        'layanan_id', 'user_id', 'nomor_tiket', 'status',
        'tanggal_pengajuan', 'tanggal_selesai',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pengajuan' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dokumens()
    {
        return $this->hasMany(Dokumen::class);
    }

    public function riwayatDisposisis()
    {
        return $this->hasMany(RiwayatDisposisi::class)->orderBy('tanggal_disposisi');
    }
}