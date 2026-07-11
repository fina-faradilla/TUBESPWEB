<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD

class Laporan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'judul',
        'pelapor',
        'kategori',
        'status',
        'tanggal',
        'foto',
        'latitude',
        'longitude',
        'alamat',
        
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Daftar status yang valid, urut sesuai alur proses.
    public const STATUS_OPTIONS = ['BARU', 'DIVERIFIKASI', 'DIPROSES', 'SELESAI'];

    // Daftar kategori kerusakan sekarang dikelola lewat model Kategori (tabel `kategoris`).
    // Lihat App\Models\Kategori.

    /**
     * Warna badge Tailwind sesuai status (dipakai di view).
     */
    public function statusColorClass(): string
    {
        return match ($this->status) {
            'BARU'         => 'badge-baru',
            'DIPROSES'     => 'badge-diproses',
            'DIVERIFIKASI' => 'badge-diverifikasi',
            'SELESAI'      => 'badge-selesai',
            default        => '',
        };
    }

    /**
     * Status berikutnya dalam alur BARU -> DIVERIFIKASI -> DIPROSES -> SELESAI.
     * Null jika sudah di status terakhir.
     */
    public function statusBerikutnya(): ?string
    {
        $idx = array_search($this->status, self::STATUS_OPTIONS, true);
        if ($idx === false || $idx === count(self::STATUS_OPTIONS) - 1) {
            return null;
        }
        return self::STATUS_OPTIONS[$idx + 1];
    }

    /**
     * Generate kode laporan berikutnya, mis. JK-0144.
     */
    /**
     * Generate kode laporan berikutnya, mis. RF-0144.
     * Diambil dari angka KODE terbesar yang ada (bukan id terakhir),
     * supaya tidak bentrok kalau urutan insert id tidak sejalan dengan urutan kode.
     */
    public static function generateKode(): string
    {
        $maxNumber = static::query()
            ->selectRaw("MAX(CAST(SUBSTRING(kode, 4) AS UNSIGNED)) as max_num")
            ->value('max_num');

        $lastNumber = $maxNumber ?? 143;

        return 'RF-' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    }

public function fotoUrl(): ?string
{
    return $this->foto ? asset('storage/' . $this->foto) : null;
}

public function punyaLokasi(): bool
{
    return $this->latitude !== null && $this->longitude !== null;
}
}
=======
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
>>>>>>> origin/desti
