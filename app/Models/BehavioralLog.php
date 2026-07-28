<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BehavioralLog extends Model
{
    protected $fillable = [
        'student_id',
        'teacher_id',
        'category',
        'type',
        'points',
        'description',
    ];

    /**
     * Get the student that own the log.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the teacher that authored the log.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
