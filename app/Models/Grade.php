<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'grade',
        'min_mark',
        'max_mark',
        'points',
        'remarks',
        'color',
    ];

    protected $casts = [
        'min_mark' => 'decimal:2',
        'max_mark' => 'decimal:2',
        'points' => 'decimal:2',
    ];

    /**
     * Find the grade corresponding to a mark.
     */
    public static function calculate(float $mark): ?self
    {
        return self::query()
            ->where('min_mark', '<=', $mark)
            ->where('max_mark', '>=', $mark)
            ->first();
    }
}
