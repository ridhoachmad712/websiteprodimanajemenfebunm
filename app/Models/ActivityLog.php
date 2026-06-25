<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    public const UPDATED_AT = null; // hanya created_at

    protected $fillable = [
        'user_id',
        'user_name',
        'action',
        'subject_type',
        'subject_label',
        'subject_id',
    ];

    /** Label tipe subjek ramah untuk ditampilkan. */
    public const SUBJECT_LABELS = [
        'Post' => 'Berita',
        'Pengumuman' => 'Pengumuman',
        'Dosen' => 'Dosen',
        'Prestasi' => 'Prestasi',
        'Download' => 'Dokumen',
        'Seminar' => 'Seminar',
        'Mitra' => 'Mitra',
        'Kegiatan' => 'Kegiatan',
        'Page' => 'Halaman',
        'Menu' => 'Menu',
        'Gallery' => 'Galeri',
        'User' => 'Pengguna',
    ];

    public const ACTION_META = [
        'created' => ['label' => 'Menambah', 'color' => 'green', 'icon' => 'ti-plus'],
        'updated' => ['label' => 'Mengubah', 'color' => 'azure', 'icon' => 'ti-pencil'],
        'deleted' => ['label' => 'Menghapus', 'color' => 'red', 'icon' => 'ti-trash'],
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Catat satu aktivitas pada model tertentu.
     */
    public static function record(string $action, Model $subject): void
    {
        static::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()?->name ?? 'Sistem',
            'action' => $action,
            'subject_type' => class_basename($subject),
            'subject_label' => method_exists($subject, 'activityLabel') ? $subject->activityLabel() : ('#'.$subject->getKey()),
            'subject_id' => $subject->getKey(),
        ]);
    }

    public function subjectLabelType(): string
    {
        return self::SUBJECT_LABELS[$this->subject_type] ?? $this->subject_type;
    }

    /** @return array{label: string, color: string, icon: string} */
    public function actionMeta(): array
    {
        return self::ACTION_META[$this->action] ?? ['label' => $this->action, 'color' => 'secondary', 'icon' => 'ti-point'];
    }
}
