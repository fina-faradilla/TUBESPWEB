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
        'kode',
        'tanggal',
        'user_id',
        'pelapor',
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

    /**
     * Auto-generate kolom wajib (`kode`, `tanggal`, `pelapor`) setiap kali laporan baru
     * dibuat, dari manapun (form warga ATAU form admin "Tambah Manual"), supaya kedua
     * controller tidak perlu mengisinya manual satu-satu dan tidak gampang lupa lagi.
     */
    protected static function booted(): void
    {
        static::creating(function (Laporan $laporan) {
            if (empty($laporan->kode)) {
                $laporan->kode = 'JK-' . str_pad((int) (static::max('id') + 1), 4, '0', STR_PAD_LEFT);
            }
            if (empty($laporan->tanggal)) {
                $laporan->tanggal = now()->toDateString();
            }
            if (empty($laporan->pelapor)) {
                $laporan->pelapor = optional(auth()->user())->name ?? 'Anonim';
            }
        });
    }

    // Supaya kode_laporan & nama_pelapor ikut muncul saat model di-@json() ke JavaScript
    // (dipakai modal "Ubah" di halaman Kelola Laporan admin).
    protected $appends = ['kode_laporan', 'nama_pelapor'];

    // Daftar status yang valid, urut sesuai alur proses.
    // "Ditolak" bukan bagian dari alur maju otomatis (statusBerikutnya), hanya bisa
    // dipilih manual lewat form "Ubah" di admin.
    public const STATUS_OPTIONS = ['Menunggu Verifikasi', 'Diproses', 'Selesai', 'Ditolak'];

    // Urutan alur status yang dipakai tombol "Verifikasi" untuk maju satu tahap.
    private const STATUS_FLOW = ['Menunggu Verifikasi', 'Diproses', 'Selesai'];

    /**
     * Laporan ini dibuat oleh akun warga yang mana (nullable — laporan manual
     * yang ditambahkan admin lewat "Tambah Manual" tidak selalu punya akun warga).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tindakLanjuts()
    {
        return $this->hasMany(TindakLanjut::class)->orderBy('created_at');
    }

    /**
     * Nama pelapor untuk ditampilkan: kalau laporan berasal dari akun warga,
     * pakai nama akun itu (selalu akurat & tidak bisa "dipalsukan" lewat form).
     * Kalau laporan dibuat manual oleh admin (tidak ada akun warga), pakai
     * kolom `pelapor` yang diisi manual saat itu.
     */
    public function getNamaPelaporAttribute(): string
    {
        return $this->user?->name ?? $this->pelapor ?? '—';
    }

    // Kode laporan otomatis, contoh: RF-0142
    public function getKodeLaporanAttribute(): string
    {
        return sprintf('RF-%04d', $this->id);
    }

    /**
     * Warna badge Tailwind (dipakai di view sisi warga).
     */
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

    /**
     * Warna badge Tailwind versi admin (kelas CSS di public/css/admin.css).
     */
    public function statusColorClass(): string
    {
        return match ($this->status) {
            'Menunggu Verifikasi' => 'badge-diverifikasi',
            'Diproses'             => 'badge-diproses',
            'Selesai'              => 'badge-selesai',
            'Ditolak'              => 'badge-ditolak',
            default                => '',
        };
    }

    /**
     * Status berikutnya dalam alur Menunggu Verifikasi -> Diproses -> Selesai.
     * Null jika sudah di status terakhir (Selesai) atau kalau laporan sudah Ditolak.
     */
    public function statusBerikutnya(): ?string
    {
        $idx = array_search($this->status, self::STATUS_FLOW, true);
        if ($idx === false || $idx === count(self::STATUS_FLOW) - 1) {
            return null;
        }
        return self::STATUS_FLOW[$idx + 1];
    }

    public function fotoUrl(): ?string
    {
        return $this->foto ? asset('storage/' . $this->foto) : null;
    }

    public function punyaLokasi(): bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }

    /**
     * Ubah status laporan SEKALIGUS mencatat ke riwayat tindak lanjut,
     * supaya timeline di sisi warga (Detail Laporan) otomatis ter-update.
     * Dipakai admin (Kelola Laporan) untuk tombol Verifikasi/Ubah Status/Tolak.
     */
    public function ubahStatus(string $statusBaru, string $judulLog, ?string $keterangan = null): void
    {
        $this->update(['status' => $statusBaru]);

        $this->tindakLanjuts()->create([
            'judul'      => $judulLog,
            'keterangan' => $keterangan,
        ]);
    }

    /**
     * Shortcut khusus tombol "Verifikasi" — otomatis maju satu tahap
     * (Menunggu Verifikasi -> Diproses -> Selesai) sesuai STATUS_FLOW,
     * lalu mencatat log dengan judul yang sesuai tahap tersebut.
     */
    public function majukanStatus(?string $keterangan = null): void
    {
        $statusBaru = $this->statusBerikutnya();

        if (! $statusBaru) {
            return; // sudah di status terakhir, tidak ada yang dimajukan
        }

        $judulLog = match ($statusBaru) {
            'Diproses' => 'Diverifikasi oleh Admin',
            'Selesai'  => 'Perbaikan selesai',
            default    => 'Status diperbarui',
        };

        $this->ubahStatus($statusBaru, $judulLog, $keterangan);
    }
}
