<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model
{
    protected $fillable = [
        'assignment_id',
        'student_id',
        'content',
        'answers',
        'file_path',
        'score',
        'max_score',
        'graded_by',
        'feedback',
        'graded_at',
        'submitted_at',
    ];

    protected $casts = [
        'answers' => 'array',
        'score' => 'integer',
        'max_score' => 'integer',
        'graded_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    // ── Relationships ──

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function grader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    // ── Helpers ──

    public function isGraded(): bool
    {
        return $this->graded_at !== null;
    }

    public function percentage(): ?float
    {
        if ($this->score === null || $this->max_score === null || $this->max_score === 0) {
            return null;
        }

        return round(($this->score / $this->max_score) * 100, 1);
    }

    public function letterGrade(): ?string
    {
        $pct = $this->percentage();
        if ($pct === null) {
            return null;
        }

        return match (true) {
            $pct >= 97 => 'A+',
            $pct >= 93 => 'A',
            $pct >= 90 => 'A-',
            $pct >= 87 => 'B+',
            $pct >= 83 => 'B',
            $pct >= 80 => 'B-',
            $pct >= 77 => 'C+',
            $pct >= 73 => 'C',
            $pct >= 70 => 'C-',
            $pct >= 67 => 'D+',
            $pct >= 63 => 'D',
            $pct >= 60 => 'D-',
            default => 'F',
        };
    }
}
