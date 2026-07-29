<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Classroom;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClassroomPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the classroom stream.
     */
    public function view(User $user, Classroom $classroom): bool
    {
        // Admins, super admins, and staff can view any stream
        if (in_array($user->role, ['admin', 'super_admin', 'staff'])) {
            return true;
        }

        // Teachers can view streams of classrooms they own
        if ($user->role === 'teacher' && $classroom->teacher_id === $user->id) {
            return true;
        }

        // Parents/Students can view streams only if they are enrolled (pivot table) – optional
        if (in_array($user->role, ['parent', 'student'])) {
            return $classroom->students()->where('students.id', $user->id)->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can manage (start/stop) the classroom stream.
     */
    public function manage(User $user, Classroom $classroom): bool
    {
        // Only admins, super admins, and the teacher who owns the classroom can manage
        return $user->role === 'admin' ||
            $user->role === 'super_admin' ||
            ($user->role === 'teacher' && $classroom->teacher_id === $user->id);
    }
}

?>
