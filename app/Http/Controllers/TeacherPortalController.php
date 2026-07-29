<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\BehavioralLog;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Submission;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TeacherPortalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $schoolId = $user->school_id;

        // Today's schedule from DB
        $dailySchedule = Schedule::where('teacher_id', $user->id)
            ->where('day_of_week', now()->dayOfWeek)
            ->orderBy('start_time')
            ->get()
            ->map(function ($s) {
                $now = now();
                $start = Carbon::parse($s->start_time);
                $end = Carbon::parse($s->end_time);

                return [
                    'id' => $s->id,
                    'name' => $s->subject_name,
                    'time' => $start->format('H:i').' - '.$end->format('H:i'),
                    'room' => $s->room_id,
                    'status' => $now->gt($end) ? 'completed' : ($now->between($start, $end) ? 'active' : 'upcoming'),
                ];
            });

        // Stats
        $totalAssignments = Assignment::where('teacher_id', $user->id)->count();
        $pendingGrading = Submission::whereHas('assignment', function ($q) use ($user) {
            $q->where('teacher_id', $user->id);
        })
            ->whereNull('graded_at')
            ->count();

        // Recent behavioral logs by this teacher
        $recentBehavior = BehavioralLog::where('teacher_id', $user->id)
            ->with('student:id,first_name,last_name')
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn ($b) => [
                'student' => $b->student?->full_name,
                'type' => $b->type,
                'category' => $b->category,
                'points' => $b->points,
                'date' => $b->created_at->diffForHumans(),
            ]);

        // Upcoming assignment deadlines
        $upcomingDeadlines = Assignment::where('teacher_id', $user->id)
            ->where('is_published', true)
            ->where('due_at', '>', now())
            ->orderBy('due_at')
            ->limit(5)
            ->get()
            ->map(fn ($a) => [
                'id' => $a->id,
                'title' => $a->title,
                'subject' => $a->subject,
                'type' => $a->type,
                'due_at' => $a->due_at->toISOString(),
                'due_label' => $a->due_at->diffForHumans(),
                'room_id' => $a->room_id,
            ]);

        // Get all rooms this teacher is assigned to
        $rooms = Schedule::where('teacher_id', $user->id)
            ->distinct()
            ->pluck('room_id');

        // Average behavior score across their rooms
        $studentIds = Student::where('school_id', $schoolId)
            ->whereIn('room_id', $rooms)
            ->pluck('id');

        $avgBehavior = BehavioralLog::whereIn('student_id', $studentIds)->avg('points') ?? 0;

        return Inertia::render('Teacher/Dashboard', [
            'schedule' => $dailySchedule,
            'recentBehavior' => $recentBehavior,
            'upcomingDeadlines' => $upcomingDeadlines,
            'stats' => [
                'total_assignments' => $totalAssignments,
                'pending_grading' => $pendingGrading,
                'behavior_score' => round($avgBehavior, 1),
                'student_count' => $studentIds->count(),
            ],
        ]);
    }
}
