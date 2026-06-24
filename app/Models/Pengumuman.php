<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pengumuman extends Model
{
    protected $table = 'pengumuman';

    protected $fillable = [
        'judul',
        'slug',
        'konten',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function isPublished(): bool
    {
        return ($this->status ?? 'draft') === 'published'
            && $this->published_at !== null
            && $this->published_at->lte(now());
    }

    /**
     * Slug pendek & unik dari judul.
     */
    public static function generateSlug(string $judul, ?int $ignoreId = null): string
    {
        $base = Str::slug($judul) ?: 'pengumuman';
        $slug = $base;
        $i = 1;

        while (static::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    /**
     * Ringkasan singkat tanpa HTML untuk daftar/kartu.
     */
    public function ringkasan(int $length = 120): string
    {
        return Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($this->konten ?? ''))), $length);
    }

    public function url(): string
    {
        return route('pengumuman.show', $this);
    }
}
