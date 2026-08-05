<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use LogsActivity;

    /**
     * Nama tabel eksplisit (model singular "Dosen", tabel "dosen").
     */
    protected $table = 'dosen';

    protected $fillable = [
        'nama',
        'jabatan',
        'slug',
        'nip',
        'foto',
        'kategori',
        'konsentrasi',
        'kepakaran',
        'biografi',
        'bio_link',
        'link_scholar',
        'link_sinta',
        'link_orcid',
        'tautan',
        'urutan',
    ];

    protected $casts = [
        'tautan' => 'array',
    ];

    /**
     * Tautan profil default yang muncul di setiap halaman dosen.
     * Admin cukup mengisi URL-nya. Key = nama kolom.
     *
     * @return array<string, array{label: string, icon: string, color: string}>
     */
    public static function profilLinks(): array
    {
        return [
            'link_scholar' => ['label' => 'Google Scholar', 'icon' => 'ti-school', 'color' => 'azure'],
            'link_sinta' => ['label' => 'SINTA', 'icon' => 'ti-id-badge-2', 'color' => 'orange'],
            'link_orcid' => ['label' => 'ORCID', 'icon' => 'ti-fingerprint', 'color' => 'green'],
        ];
    }

    /**
     * Label kategori untuk ditampilkan & dipakai pengelompokan di halaman publik.
     */
    public const KATEGORI = [
        'guru_besar' => 'Guru Besar',
        'tetap_prodi' => 'Dosen Tetap Program Studi',
        'mkdu' => 'Dosen Tetap MKDU',
        'luar_biasa' => 'Dosen Luar Biasa',
    ];

    /** Pilihan konsentrasi MANAJEMEN (guru besar, tetap prodi, luar biasa). */
    public const KONSENTRASI_MANAJEMEN = [
        'Manajemen SDM',
        'Manajemen Pemasaran',
        'Manajemen Keuangan',
    ];

    /** Pilihan konsentrasi MKDU. */
    public const KONSENTRASI_MKDU = [
        'Pendidikan Agama Islam',
        'Kewarganegaraan',
        'Bahasa Indonesia',
        'Bahasa Inggris',
    ];

    /**
     * Peta pilihan konsentrasi per kategori (untuk dropdown bertingkat).
     *
     * @return array<string, array<int, string>>
     */
    public static function konsentrasiMap(): array
    {
        return [
            'guru_besar' => self::KONSENTRASI_MANAJEMEN,
            'tetap_prodi' => self::KONSENTRASI_MANAJEMEN,
            'luar_biasa' => self::KONSENTRASI_MANAJEMEN,
            'mkdu' => self::KONSENTRASI_MKDU,
        ];
    }

    /**
     * Ikon (Tabler) + warna badge untuk sebuah konsentrasi.
     *
     * @return array{icon: string, color: string}
     */
    public static function konsentrasiMeta(?string $konsentrasi): array
    {
        return match ($konsentrasi) {
            'Manajemen Keuangan' => ['icon' => 'ti-coin', 'color' => 'green'],
            'Manajemen Pemasaran' => ['icon' => 'ti-speakerphone', 'color' => 'azure'],
            'Manajemen SDM' => ['icon' => 'ti-users-group', 'color' => 'orange'],
            'Pendidikan Agama Islam' => ['icon' => 'ti-moon-stars', 'color' => 'teal'],
            'Kewarganegaraan' => ['icon' => 'ti-flag', 'color' => 'red'],
            'Bahasa Indonesia' => ['icon' => 'ti-language', 'color' => 'purple'],
            'Bahasa Inggris' => ['icon' => 'ti-abc', 'color' => 'indigo'],
            default => ['icon' => 'ti-bookmark', 'color' => 'blue'],
        };
    }

    public function getKategoriLabelAttribute(): string
    {
        return self::KATEGORI[$this->kategori] ?? $this->kategori;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
