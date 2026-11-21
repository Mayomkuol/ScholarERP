<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentDocument extends Model
{
    protected $fillable = [
        'student_id',
        'document_type',
        'title',
        'file',
        'remarks',
    ];

    /**
     * Get the student who owns this document.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
