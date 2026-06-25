<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Menu extends Model
{
    use LogsActivity;

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
}
