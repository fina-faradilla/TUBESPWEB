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

    public function tindakLanjuts()
{
    return $this->hasMany(TindakLanjut::class)->orderBy('created_at', 'asc');
}
}
