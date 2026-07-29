<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ParentPortalController extends Controller
{
    /**
     * Parent dashboard with children overview.
     */
    public function index()
    {
        $user = Auth::user();

        $children = $user->children()->get()->map(function ($child) {
            $latestGrades = $child->grades()->latest()->limit(3)->get();
            $recentBehavior = $child->behavioralLogs()->with('teacher:id,name')->latest()->limit(3)->get();
            $pendingAssignments = $child->availableAssignments()
                ->whereDoesntHave('submissions', function ($q) use ($child) {
                    $q->where('student_id', $child->id);
                })
                ->count();

            return [
                'id' => $child->id,
                'name' => $child->full_name,
                'admission_number' => $child->admission_number,
                'room_id' => $child->room_id,
                'gpa' => $child->gpa(),
                'behavior_score' => $child->behaviorScore(),
                'pending_assignments' => $pendingAssignments,
                'attendance_rate' => '96%', // Placeholder
                'status' => $child->current_bus_id ? 'In Transit' : 'In School',
                'latest_grades' => $latestGrades->map(fn ($g) => [
                    'subject' => $g->subject,
                    'score' => $g->score,
                    'max_score' => $g->max_score,
                    'letter_grade' => $g->letter_grade,
                    'percentage' => $g->percentage(),
                ]),
                'recent_behavior' => $recentBehavior->map(fn ($b) => [
                    'category' => $b->category,
                    'type' => $b->type,
                    'points' => $b->points,
                    'description' => $b->description,
                    'teacher' => $b->teacher?->name,
                    'date' => $b->created_at->diffForHumans(),
                ]),
            ];
        });

        // Announcements
        $announcements = Announcement::where('school_id', $user->school_id)
            ->where(function ($q) {
                $q->where('target_role', 'all')->orWhere('target_role', 'parent');
            })
            ->latest()
            ->limit(5)
            ->get();

        return Inertia::render('Parent/Dashboard', [
            'children' => $children,
            'announcements' => $announcements,
        ]);
    }

    /**
     * View a child's full grade report.
     */
    public function childGrades(Student $student)
    {
        $user = Auth::user();
        // Verify this student belongs to this parent
        abort_unless($user->children()->where('students.id', $student->id)->exists(), 403);

        $grades = $student->grades()
            ->with('teacher:id,name')
            ->orderBy('subject')
            ->orderBy('term')
            ->get()
            ->map(fn ($g) => [
                'id' => $g->id,
                'subject' => $g->subject,
                'term' => $g->term,
                'score' => $g->score,
                'max_score' => $g->max_score,
                'letter_grade' => $g->letter_grade,
                'percentage' => $g->percentage(),
                'teacher' => $g->teacher?->name,
                'remarks' => $g->remarks,
                'created_at' => $g->created_at->toISOString(),
            ]);

        $subjects = $grades->groupBy('subject')->map(function ($items, $subject) {
            $avgPct = $items->avg('percentage');

            return [
                'subject' => $subject,
                'average' => round($avgPct, 1),
                'letter' => Grade::computeLetterGrade($avgPct, 100),
                'count' => $items->count(),
            ];
        })->values();

        // Recent submissions with scores
        $submissions = Submission::where('student_id', $student->id)
            ->with(['assignment:id,title,subject,type,total_points'])
            ->whereNotNull('graded_at')
            ->latest('graded_at')
            ->limit(10)
            ->get()
            ->map(fn ($s) => [
                'title' => $s->assignment?->title,
                'subject' => $s->assignment?->subject,
                'type' => $s->assignment?->type,
                'score' => $s->score,
                'max_score' => $s->max_score,
                'percentage' => $s->percentage(),
                'letter_grade' => $s->letterGrade(),
                'graded_at' => $s->graded_at->toISOString(),
            ]);

        return Inertia::render('Parent/ChildGrades', [
            'student' => [
                'id' => $student->id,
                'name' => $student->full_name,
                'room_id' => $student->room_id,
                'admission_number' => $student->admission_number,
                'gpa' => $student->gpa(),
            ],
            'grades' => $grades,
            'subjects' => $subjects,
            'submissions' => $submissions,
        ]);
    }

    /**
     * View a child's behavioral history.
     */
    public function childBehavior(Student $student)
    {
        $user = Auth::user();
        abort_unless($user->children()->where('students.id', $student->id)->exists(), 403);

        $logs = $student->behavioralLogs()
            ->with('teacher:id,name')
            ->latest()
            ->get()
            ->map(fn ($b) => [
                'id' => $b->id,
                'category' => $b->category,
                'type' => $b->type,
                'points' => $b->points,
                'description' => $b->description,
                'teacher' => $b->teacher?->name,
                'created_at' => $b->created_at->toISOString(),
                'date' => $b->created_at->diffForHumans(),
            ]);

        $summary = [
            'total_score' => $student->behaviorScore(),
            'kudos_count' => $student->behavioralLogs()->where('type', 'kudos')->count(),
            'incident_count' => $student->behavioralLogs()->where('type', 'incident')->count(),
            'kudos_points' => $student->behavioralLogs()->where('type', 'kudos')->sum('points'),
            'incident_points' => $student->behavioralLogs()->where('type', 'incident')->sum('points'),
        ];

        return Inertia::render('Parent/ChildBehavior', [
            'student' => [
                'id' => $student->id,
                'name' => $student->full_name,
                'room_id' => $student->room_id,
            ],
            'logs' => $logs,
            'summary' => $summary,
        ]);
    }
}
