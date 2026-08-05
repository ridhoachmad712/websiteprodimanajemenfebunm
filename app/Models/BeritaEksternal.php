<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BeritaEksternal extends Model
{
    use LogsActivity;

    protected $table = 'berita_eksternal';

    protected $fillable = [
        'judul',
        'sumber',
        'url',
        'tanggal',
        'ringkasan',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function isPublished(): bool
    {
        return ($this->status ?? 'published') === 'published';
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }
}
