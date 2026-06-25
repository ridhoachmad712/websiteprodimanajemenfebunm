<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use LogsActivity;

    protected $fillable = [
        'judul',
        'gambar',
        'kategori',
        'urutan',
    ];
}
