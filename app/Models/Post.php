<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Post extends Model
{
    use LogsActivity;

    protected $fillable = [
        'judul',
        'jenis',
        'slug',
        'excerpt',
        'konten',
        'featured_image',
        'user_id',
        'status',
        'submitted_at',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Hanya post terbit dan sudah lewat jadwal publikasinya.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public const JENIS = [
        'berita' => 'Berita',
        'artikel' => 'Artikel',
        'opini' => 'Opini',
    ];

    public function isSubmitted(): bool
    {
        return $this->status === 'draft' && $this->submitted_at !== null;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Maksimal jumlah kata pada slug agar URL tetap pendek. */
    public const SLUG_MAX_WORDS = 6;

    /**
     * Hasilkan slug PENDEK & unik dari judul (ambil beberapa kata pertama).
     */
    public static function generateSlug(string $judul, ?int $ignoreId = null): string
    {
        $words = array_values(array_filter(explode('-', Str::slug($judul))));
        $base = implode('-', array_slice($words, 0, self::SLUG_MAX_WORDS)) ?: 'post';

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
     * Ringkasan singkat untuk kartu/daftar: pakai excerpt bila ada,
     * jika tidak otomatis dari isi (tanpa HTML), dipangkas rapi.
     */
    public function ringkasan(int $length = 120): string
    {
        $teks = filled($this->excerpt) ? $this->excerpt : strip_tags($this->konten ?? '');

        return Str::limit(trim(preg_replace('/\s+/', ' ', $teks)), $length);
    }

    /**
     * URL publik berita dengan pola WordPress: /YYYY/MM/DD/slug.
     */
    public function url(): string
    {
        $date = $this->published_at ?? $this->created_at;

        return route('post.show', [
            'year' => $date->format('Y'),
            'month' => $date->format('m'),
            'day' => $date->format('d'),
            'slug' => $this->slug,
        ]);
    }
}
