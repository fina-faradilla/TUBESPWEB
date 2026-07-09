<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Laporan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'laporans';

    protected $fillable = [
        'user_id',
        'judul',
        'kategori',
        'tingkat',
        'alamat',
        'deskripsi',
        'foto',
        'latitude',
        'longitude',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tindakLanjuts()
    {
        return $this->hasMany(TindakLanjut::class)->orderBy('created_at');
    }

    // Kode laporan otomatis, contoh: RF-0142
    public function getKodeLaporanAttribute(): string
    {
        return sprintf('RF-%04d', $this->id);
    }

    public function tingkatBadgeColor(): string
    {
        return match ($this->tingkat) {
            'Ringan' => 'bg-green-500/20 text-green-400 border border-green-500/40',
            'Sedang' => 'bg-amber-500/20 text-amber-400 border border-amber-500/40',
            'Berat'  => 'bg-red-500/20 text-red-400 border border-red-500/40',
            default  => 'bg-gray-500/20 text-gray-300 border border-gray-500/40',
        };
    }

    public function statusBadgeColor(): string
    {
        return match ($this->status) {
            'Menunggu Verifikasi' => 'bg-transparent text-amber-400 border border-amber-400/50',
            'Diproses'             => 'bg-blue-500/20 text-blue-400 border border-blue-500/40',
            'Selesai'              => 'bg-green-500/20 text-green-400 border border-green-500/40',
            'Ditolak'              => 'bg-red-500/20 text-red-400 border border-red-500/40',
            default                => 'bg-gray-500/20 text-gray-300 border border-gray-500/40',
        };
    }
}
