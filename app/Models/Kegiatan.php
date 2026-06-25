<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use LogsActivity;

    protected $table = 'kegiatan';

    protected $fillable = [
        'judul',
        'mulai',
        'selesai',
        'seharian',
        'lokasi',
        'deskripsi',
        'warna',
    ];

    protected $casts = [
        'mulai' => 'datetime',
        'selesai' => 'datetime',
        'seharian' => 'boolean',
    ];

    /**
     * Kegiatan yang belum/akan berlangsung (selesai—atau mulai bila tanpa selesai—≥ hari ini).
     */
    public function scopeMendatang(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereDate('selesai', '>=', today())
                ->orWhere(function ($q2) {
                    $q2->whereNull('selesai')->whereDate('mulai', '>=', today());
                });
        });
    }

    /**
     * Representasi event untuk FullCalendar.
     *
     * @return array<string, mixed>
     */
    public function toEvent(): array
    {
        // FullCalendar: untuk all-day, 'end' bersifat eksklusif → tambah 1 hari.
        $end = null;
        if ($this->selesai) {
            $end = $this->seharian
                ? $this->selesai->copy()->addDay()->toDateString()
                : $this->selesai->toIso8601String();
        }

        return [
            'title' => $this->judul,
            'start' => $this->seharian ? $this->mulai->toDateString() : $this->mulai->toIso8601String(),
            'end' => $end,
            'allDay' => $this->seharian,
            'color' => $this->warna ?: Setting::get('theme.primary', '#1b3a5b'),
            'extendedProps' => [
                'lokasi' => $this->lokasi,
                'deskripsi' => $this->deskripsi,
            ],
        ];
    }
}
