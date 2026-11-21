<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TimetablePeriod extends Model
{
    protected $fillable = [
        'name',
        'period_number',
        'start_time',
        'end_time',
        'type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Timetable entries using this period.
     */
    public function timetables(): HasMany
    {
        return $this->hasMany(Timetable::class, 'period_id');
    }

    /**
     * Check whether this is a teaching period.
     */
    public function isLesson(): bool
    {
        return $this->type === 'lesson';
    }

    /**
     * Check whether this is a break.
     */
    public function isBreak(): bool
    {
        return $this->type === 'break';
    }

    /**
     * Check whether this is lunch.
     */
    public function isLunch(): bool
    {
        return $this->type === 'lunch';
    }
}