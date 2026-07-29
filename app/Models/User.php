<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'school_id',
        'role',
        'is_suspended',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the students associated with the parent.
     */
    public function children()
    {
        return $this->belongsToMany(Student::class, 'parent_student', 'parent_id', 'student_id');
    }

    public function students()
    {
        return $this->children();
    }

    /**
     * Get the Student record linked to this user (for student-role users).
     */
    public function studentRecord()
    {
        return $this->hasOne(Student::class, 'user_id');
    }

    /**
     * Get the Staff record linked to this user.
     */
    public function staffProfile()
    {
        return $this->hasOne(StaffProfile::class, 'user_id');
    }

    /**
     * Assignments created by this teacher.
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'teacher_id');
    }

    /**
     * Behavioral logs authored by this teacher.
     */
    public function behavioralLogs()
    {
        return $this->hasMany(BehavioralLog::class, 'teacher_id');
    }

    /**
     * Grades recorded by this teacher.
     */
    public function recordedGrades()
    {
        return $this->hasMany(Grade::class, 'teacher_id');
    }

    // ── Role Helpers ──

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->role === 'super_admin';
    }

    public function isTeacher(): bool
    {
        return $this->role === 'teacher' || $this->role === 'staff';
    }

    public function isAccountant(): bool
    {
        return $this->role === 'accountant';
    }

    public function isLibrarian(): bool
    {
        return $this->role === 'librarian';
    }

    public function isReceptionist(): bool
    {
        return $this->role === 'receptionist';
    }

    public function isParent(): bool
    {
        return $this->role === 'parent';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Get the school ID currently in scope.
     * For Super Admins, this can be switched via session.
     */
    public function getScopedSchoolId()
    {
        if ($this->isSuperAdmin() && session()->has('active_school_id')) {
            return session('active_school_id');
        }

        return $this->school_id;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
