<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    /**
     * Nama tabel eksplisit (model singular "Dosen", tabel "dosen").
     */
    protected $table = 'dosen';

    protected $fillable = [
        'nama',
        'slug',
        'nip',
        'foto',
        'kategori',
        'konsentrasi',
        'bio_link',
        'urutan',
    ];

    /**
     * Label kategori untuk ditampilkan & dipakai pengelompokan di halaman publik.
     */
    public const KATEGORI = [
        'guru_besar'  => 'Guru Besar',
        'tetap_prodi' => 'Dosen Tetap Program Studi',
        'mkdu'        => 'Dosen Tetap MKDU',
        'luar_biasa'  => 'Dosen Luar Biasa',
    ];

    public function getKategoriLabelAttribute(): string
    {
        return self::KATEGORI[$this->kategori] ?? $this->kategori;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
