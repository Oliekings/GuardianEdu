<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    protected $fillable = [
        'student_id',
        'school_id',
        'subject',
        'term',
        'score',
        'max_score',
        'letter_grade',
        'teacher_id',
        'remarks',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'max_score' => 'decimal:2',
    ];

    // ── Relationships ──

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // ── Helpers ──

    public function percentage(): float
    {
        if ($this->max_score == 0) return 0;
        return round(($this->score / $this->max_score) * 100, 1);
    }

    /**
     * Auto-compute letter grade from percentage.
     */
    public static function computeLetterGrade(float $score, float $maxScore): string
    {
        if ($maxScore == 0) return 'N/A';
        $pct = ($score / $maxScore) * 100;

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
