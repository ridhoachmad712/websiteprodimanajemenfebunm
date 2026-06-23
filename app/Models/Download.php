<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Download extends Model
{
    protected $fillable = [
        'judul',
        'kategori',
        'deskripsi',
        'file',
        'url',
        'urutan',
        'status',
    ];

    /** Kategori yang umum dipakai (untuk datalist di form admin). */
    public const KATEGORI_UMUM = [
        'Akademik', 'Formulir', 'SK Mengajar', 'RPS', 'Panduan', 'Kurikulum', 'Mutu / SOP',
    ];

    public function isPublished(): bool
    {
        return ($this->status ?? 'published') === 'published';
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    /** URL yang dituju saat tombol unduh/buka diklik (file lokal diutamakan). */
    public function tautan(): ?string
    {
        if ($this->file) {
            return Storage::url($this->file);
        }

        return $this->url ?: null;
    }

    /** Ekstensi file (uppercase) untuk badge jenis, mis. PDF/DOCX; null bila tautan eksternal. */
    public function ekstensi(): ?string
    {
        if (! $this->file) {
            return null;
        }

        $ext = pathinfo($this->file, PATHINFO_EXTENSION);

        return $ext !== '' ? strtoupper($ext) : null;
    }
}
