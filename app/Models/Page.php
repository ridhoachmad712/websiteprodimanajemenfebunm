<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
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

    public function getRouteKeyName(): string
    {
        return 'slug';
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

        $static = ['akreditasi', 'fasilitas', 'sop-petaprosesbisnis', 'kalender-akademik', 'kurikulum', 'hima', 'alumni', 'icoman2025', 'hubungi-kami'];

        return in_array($this->slug, $static, true)
            ? route('page.'.$this->slug)
            : route('page.custom', $this);
    }
}
