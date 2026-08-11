<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatDisposisi extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengajuan_id', 'tim_kerja_id', 'handled_by',
        'status', 'keterangan', 'tanggal_disposisi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_disposisi' => 'datetime',
        ];
    }

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function timKerja()
    {
        return $this->belongsTo(TimKerja::class);
    }

    public function handledBy()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }
}