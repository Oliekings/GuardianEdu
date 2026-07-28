<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    protected $fillable = [
        'school_id',
        'teacher_id',
        'subject',
        'title',
        'type',
        'room_id',
        'description',
        'due_at',
        'total_points',
        'time_limit_minutes',
        'is_published',
        'questions',
    ];

    protected $casts = [
        'due_at' => 'datetime',
        'is_published' => 'boolean',
        'questions' => 'array',
        'total_points' => 'integer',
        'time_limit_minutes' => 'integer',
    ];

    // ── Scopes ──

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeForRoom($query, string $roomId)
    {
        return $query->where('room_id', $roomId);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    // ── Relationships ──

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    // ── Helpers ──

    public function isTest(): bool
    {
        return in_array($this->type, ['test', 'quiz']);
    }

    public function isPastDue(): bool
    {
        return $this->due_at && $this->due_at->isPast();
    }

    public function submissionCount(): int
    {
        return $this->submissions()->count();
    }

    public function gradedCount(): int
    {
        return $this->submissions()->whereNotNull('graded_at')->count();
    }
}
