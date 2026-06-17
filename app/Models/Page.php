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
    ];

    protected $casts = [
        'sections' => 'array',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
