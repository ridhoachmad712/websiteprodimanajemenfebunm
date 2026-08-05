<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    use LogsActivity;

    protected $table = 'hero_slides';

    protected $fillable = [
        'gambar',
        'judul',
        'subjudul',
        'btn_label',
        'btn_url',
        'urutan',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('aktif', true);
    }

    public function activityLabel(): string
    {
        return $this->judul ?: 'Slide #'.$this->getKey();
    }
}
