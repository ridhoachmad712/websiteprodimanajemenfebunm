<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Menu extends Model
{
    protected $fillable = [
        'parent_id',
        'title',
        'url',
        'target',
        'urutan',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('urutan');
    }

    /**
     * Anak menu yang aktif & terurut — dipakai merender mega-menu.
     */
    public function activeChildren(): HasMany
    {
        return $this->children()->where('aktif', true);
    }

    /**
     * URL final untuk href: eksternal apa adanya, internal lewat url(),
     * placeholder '#' bila kosong (item induk).
     */
    public function getHrefAttribute(): string
    {
        $u = $this->url;

        if (! $u || $u === '#') {
            return '#';
        }

        return Str::startsWith($u, ['http://', 'https://']) ? $u : url($u);
    }

    protected static function booted(): void
    {
        // Segarkan cache menu publik setiap kali data menu berubah.
        static::saved(fn () => Cache::forget('menu.main'));
        static::deleted(fn () => Cache::forget('menu.main'));
    }
}
