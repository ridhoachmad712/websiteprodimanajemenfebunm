<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Seminar extends Model
{
    use LogsActivity;

    protected $fillable = [
        'nama',
        'nim',
        'judul',
        'jenis',
        'tanggal',
        'tempat',
        'pembimbing',
        'penguji',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    /** @return array<string, string> */
    public static function jenisOptions(): array
    {
        return [
            'proposal' => 'Seminar Proposal',
            'hasil' => 'Seminar Hasil',
            'tutup' => 'Ujian Tutup',
        ];
    }

    /**
     * Warna Tabler untuk badge jenis.
     */
    public static function jenisColor(?string $jenis): string
    {
        return match ($jenis) {
            'proposal' => 'azure',
            'hasil' => 'green',
            'tutup' => 'purple',
            default => 'blue',
        };
    }

    public function jenisLabel(): string
    {
        return static::jenisOptions()[$this->jenis] ?? ucfirst((string) $this->jenis);
    }

    public function isPublished(): bool
    {
        return ($this->status ?? 'published') === 'published';
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeMendatang(Builder $query): Builder
    {
        return $query->where('tanggal', '>=', now()->startOfDay());
    }

    public function scopeLalu(Builder $query): Builder
    {
        return $query->where('tanggal', '<', now()->startOfDay());
    }
}
