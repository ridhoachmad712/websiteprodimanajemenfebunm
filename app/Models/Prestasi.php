<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    use LogsActivity;

    protected $table = 'prestasi';

    protected $fillable = [
        'judul',
        'kategori',
        'tingkat',
        'peraih',
        'penyelenggara',
        'tanggal',
        'gambar',
        'deskripsi',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /** @return array<string, string> */
    public static function kategoriOptions(): array
    {
        return [
            'mahasiswa' => 'Mahasiswa',
            'dosen' => 'Dosen',
            'prodi' => 'Program Studi',
        ];
    }

    /** @return array<string, string> */
    public static function tingkatOptions(): array
    {
        return [
            'lokal' => 'Lokal',
            'regional' => 'Regional',
            'nasional' => 'Nasional',
            'internasional' => 'Internasional',
        ];
    }

    /**
     * Ikon + warna Tabler untuk badge tingkat.
     *
     * @return array{icon: string, color: string}
     */
    public static function tingkatMeta(?string $tingkat): array
    {
        return match ($tingkat) {
            'internasional' => ['icon' => 'ti-world', 'color' => 'purple'],
            'nasional' => ['icon' => 'ti-flag', 'color' => 'red'],
            'regional' => ['icon' => 'ti-map-2', 'color' => 'orange'],
            'lokal' => ['icon' => 'ti-map-pin', 'color' => 'blue'],
            default => ['icon' => 'ti-trophy', 'color' => 'azure'],
        };
    }

    public function isPublished(): bool
    {
        return ($this->status ?? 'published') === 'published';
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }
}
