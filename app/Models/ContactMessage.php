<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'nama',
        'email',
        'subjek',
        'pesan',
        'dibaca',
    ];

    protected $casts = [
        'dibaca' => 'boolean',
    ];

    public function scopeUnread($query)
    {
        return $query->where('dibaca', false);
    }
}
