<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $fillable = ['nama'];

    /** Jumlah laporan yang memakai kategori ini. */
    public function jumlahLaporan(): int
    {
        return Laporan::where('kategori', $this->nama)->count();
    }
}
