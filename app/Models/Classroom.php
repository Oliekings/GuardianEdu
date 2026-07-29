<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Classroom extends Model
{
    protected $fillable = [
        'name',
        'teacher_id',
        'stream_key',
        'stream_url',
    ];

    /**
     * The teacher that owns the classroom.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Attendances recorded for this classroom.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Students enrolled in this classroom (many‑to‑many).
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'classroom_student');
    }
}
