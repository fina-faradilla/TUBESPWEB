<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TindakLanjut extends Model
{
    use HasFactory;

    protected $fillable = [
        'laporan_id',
        'judul',
        'keterangan',
    ];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }
}