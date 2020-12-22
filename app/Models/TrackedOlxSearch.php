<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

// id
// url
// tracked_pages_amount
// created_at
// updated_at
// active

class TrackedOlxSearch extends Model
{
    protected $dates = [
        'deleted_at',
        'last_tracked_at',
    ];

    // todo validate url
    protected $fillable = [
        'url',
        'active',
        'last_tracked_at',
        'tracking_interval', // minutes
    ];

    public function isNeedTrack(): bool
    {
        if (empty($this->last_tracked_at)) {
            return true;
        }
        /** @var Carbon $nextTrackingDate */
        $nextTrackingDate = $this->getNextTrackingDate();
        $now              = Carbon::now();
        $needTrack        = $nextTrackingDate->isBefore($now);

        return $needTrack;
    }

    protected function getNextTrackingDate(): ?Carbon
    {
        if (empty($this->last_tracked_at)) {
            return null;
        }
        $nextTrackingDate = (clone $this->last_tracked_at)->addMinutes($this->tracking_interval);

        return $nextTrackingDate;
    }

    public static function scopeActive($q)
    {
        $q->where('active', true);

        return $q;
    }

    public function isAllPagesTracked(): bool
    {
        return !is_null($this->tracked_pages_amount);
    }
}
