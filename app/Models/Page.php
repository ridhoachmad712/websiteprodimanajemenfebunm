<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use LogsActivity;

    protected $fillable = [
        'slug',
        'title',
        'content',
        'sections',
        'status',
        'meta_title',
        'meta_description',
        'og_image',
    ];

    protected $casts = [
        'sections' => 'array',
    ];

    /**
     * Slug yang terikat ke rute tetap (tidak boleh diubah, agar tidak 404).
     * Halaman selain ini bebas mengubah slug (URL /halaman/{slug}).
     */
    public const FIXED_SLUGS = [
        'profil', 'akreditasi', 'fasilitas', 'sop-petaprosesbisnis', 'kalender-akademik',
        'kurikulum', 'hima', 'alumni', 'icoman2025', 'hubungi-kami',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Slug hanya dapat diubah untuk halaman custom (bukan halaman inti/statis). */
    public function slugEditable(): bool
    {
        return ! in_array($this->slug, self::FIXED_SLUGS, true);
    }

    public function isPublished(): bool
    {
        return ($this->status ?? 'published') === 'published';
    }

    public function template(): string
    {
        return $this->sections['_template'] ?? ($this->sections ? 'sections' : 'content');
    }

    public function publicUrl(): string
    {
        if ($this->slug === 'profil') {
            return route('page.profil');
        }

        return in_array($this->slug, self::FIXED_SLUGS, true)
            ? route('page.'.$this->slug)
            : route('page.custom', $this);
    }
}
