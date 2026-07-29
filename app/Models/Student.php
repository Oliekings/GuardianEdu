<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\FeeGroup;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'school_id',
        'admission_number',
        'first_name',
        'last_name',
        'email',
        'phone',
        'dob',
        'gender',
        'category',
        'religion',
        'blood_group',
        'admission_date',
        'student_image',
        'father_name',
        'father_phone',
        'father_occupation',
        'mother_name',
        'mother_phone',
        'mother_occupation',
        'guardian_is',
        'guardian_name',
        'guardian_phone',
        'guardian_email',
        'guardian_relation',
        'guardian_address',
        'is_active',
        'rfid_token',
        'current_bus_id',
        'room_id',
        'fee_group_id',
    ];

    // ── Accessors ──

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // ── Relationships ──

    /**
     * The User account linked to this student record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'parent_student', 'student_id', 'parent_id');
    }

    public function currentBus(): BelongsTo
    {
        return $this->belongsTo(BusFleet::class, 'current_bus_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'room_id', 'room_id');
    }
    
    public function feeGroup(): BelongsTo
    {
        return $this->belongsTo(FeeGroup::class, 'fee_group_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    public function behavioralLogs(): HasMany
    {
        return $this->hasMany(BehavioralLog::class);
    }

    // ── Helpers ──

    /**
     * Get all assignments for this student's room.
     */
    public function availableAssignments()
    {
        return Assignment::where('room_id', $this->room_id)
            ->where('is_published', true)
            ->where('school_id', $this->school_id);
    }

    /**
     * Calculate GPA from grades.
     */
    public function gpa(): float
    {
        $grades = $this->grades;
        if ($grades->isEmpty()) {
            return 0.0;
        }

        $totalPoints = 0;
        $count = 0;
        foreach ($grades as $grade) {
            $pct = $grade->percentage();
            $gpaPoint = match (true) {
                $pct >= 93 => 4.0,
                $pct >= 90 => 3.7,
                $pct >= 87 => 3.3,
                $pct >= 83 => 3.0,
                $pct >= 80 => 2.7,
                $pct >= 77 => 2.3,
                $pct >= 73 => 2.0,
                $pct >= 70 => 1.7,
                $pct >= 67 => 1.3,
                $pct >= 63 => 1.0,
                $pct >= 60 => 0.7,
                default => 0.0,
            };
            $totalPoints += $gpaPoint;
            $count++;
        }

        return round($totalPoints / $count, 2);
    }

    /**
     * Calculate total behavioral points.
     */
    public function behaviorScore(): int
    {
        return $this->behavioralLogs()->sum('points');
    }
}
