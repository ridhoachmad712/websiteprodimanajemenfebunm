<?php

namespace App\Models\Concerns;

use App\Models\ActivityLog;

/**
 * Catat otomatis aktivitas create/update/delete model ke activity_logs.
 * Model boleh meng-override activityLabel() untuk label kustom.
 */
trait LogsActivity
{
    public static function bootLogsActivity(): void
    {
        static::created(fn ($model) => ActivityLog::record('created', $model));
        static::updated(fn ($model) => ActivityLog::record('updated', $model));
        static::deleted(fn ($model) => ActivityLog::record('deleted', $model));
    }

    public function activityLabel(): string
    {
        foreach (['judul', 'nama', 'title', 'name'] as $attr) {
            if (filled($this->{$attr} ?? null)) {
                return (string) $this->{$attr};
            }
        }

        return '#'.$this->getKey();
    }
}
