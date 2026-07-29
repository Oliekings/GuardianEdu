<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\Grade;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Submission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class StudentPortalController extends Controller
{
    /**
     * Student dashboard with schedule, assignments, grades, announcements.
     */
    public function index()
    {
        $user = Auth::user();
        $student = $user->studentRecord;

        if (! $student) {
            return Inertia::render('Student/Dashboard', [
                'schedule' => [],
                'pendingAssignments' => [],
                'recentGrades' => [],
                'announcements' => [],
                'stats' => [
                    'gpa' => 0,
                    'behavior_score' => 0,
                    'assignments_pending' => 0,
                    'attendance_rate' => '0%',
                ],
            ]);
        }

        // Today's schedule
        $schedule = Schedule::where('room_id', $student->room_id)
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
                    'room' => $s->room_id,
                    'time' => $start->format('H:i').' - '.$end->format('H:i'),
                    'teacher' => $s->teacher?->name ?? 'TBA',
                    'active' => $now->between($start, $end),
                    'completed' => $now->gt($end),
                ];
            });

        // Pending assignments
        $pendingAssignments = Assignment::where('room_id', $student->room_id)
            ->where('school_id', $student->school_id)
            ->where('is_published', true)
            ->whereDoesntHave('submissions', function ($q) use ($student) {
                $q->where('student_id', $student->id);
            })
            ->orderBy('due_at')
            ->limit(6)
            ->get()
            ->map(fn ($a) => [
                'id' => $a->id,
                'title' => $a->title,
                'subject' => $a->subject,
                'type' => $a->type,
                'due_at' => $a->due_at?->toISOString(),
                'due_label' => $a->due_at ? $a->due_at->diffForHumans() : 'No deadline',
                'total_points' => $a->total_points,
                'time_limit' => $a->time_limit_minutes,
                'is_past_due' => $a->isPastDue(),
            ]);

        // Recent grades
        $recentGrades = $student->grades()
            ->with('teacher:id,name')
            ->latest()
            ->limit(5)
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
            ]);

        // Announcements
        $announcements = Announcement::where('school_id', $user->school_id)
            ->where(function ($q) {
                $q->where('target_role', 'all')->orWhere('target_role', 'student');
            })
            ->latest()
            ->limit(5)
            ->get();

        // Stats
        $stats = [
            'gpa' => $student->gpa(),
            'behavior_score' => $student->behaviorScore(),
            'assignments_pending' => $pendingAssignments->count(),
            'attendance_rate' => '96%', // Placeholder — would come from RFID taps in production
        ];

        return Inertia::render('Student/Dashboard', compact(
            'schedule', 'pendingAssignments', 'recentGrades', 'announcements', 'stats'
        ));
    }

    /**
     * List all assignments for this student's room.
     */
    public function assignments(Request $request)
    {
        $student = Auth::user()->studentRecord;
        if (! $student) {
            return Inertia::render('Student/Assignments', ['assignments' => [], 'filters' => []]);
        }

        $query = Assignment::where('room_id', $student->room_id)
            ->where('school_id', $student->school_id)
            ->where('is_published', true)
            ->with(['teacher:id,name']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('subject')) {
            $query->where('subject', $request->subject);
        }

        $assignments = $query->orderBy('due_at', 'desc')->get()->map(function ($a) use ($student) {
            $submission = $a->submissions()->where('student_id', $student->id)->first();

            return [
                'id' => $a->id,
                'title' => $a->title,
                'subject' => $a->subject,
                'type' => $a->type,
                'description' => $a->description,
                'due_at' => $a->due_at?->toISOString(),
                'due_label' => $a->due_at ? $a->due_at->diffForHumans() : 'No deadline',
                'total_points' => $a->total_points,
                'time_limit' => $a->time_limit_minutes,
                'is_past_due' => $a->isPastDue(),
                'teacher' => $a->teacher?->name,
                'status' => $submission
                    ? ($submission->isGraded() ? 'graded' : 'submitted')
                    : ($a->isPastDue() ? 'overdue' : 'pending'),
                'score' => $submission?->score,
                'max_score' => $submission?->max_score,
                'feedback' => $submission?->feedback,
            ];
        });

        $subjects = Assignment::where('room_id', $student->room_id)
            ->where('school_id', $student->school_id)
            ->distinct()
            ->pluck('subject');

        return Inertia::render('Student/Assignments', [
            'assignments' => $assignments,
            'subjects' => $subjects,
            'filters' => $request->only(['type', 'subject']),
        ]);
    }

    /**
     * Show a single assignment for the student.
     */
    public function showAssignment(Assignment $assignment)
    {
        $student = Auth::user()->studentRecord;
        abort_if(! $student || $assignment->room_id !== $student->room_id, 403);

        $submission = $assignment->submissions()->where('student_id', $student->id)->first();

        return Inertia::render('Student/AssignmentShow', [
            'assignment' => [
                'id' => $assignment->id,
                'title' => $assignment->title,
                'subject' => $assignment->subject,
                'type' => $assignment->type,
                'description' => $assignment->description,
                'due_at' => $assignment->due_at?->toISOString(),
                'total_points' => $assignment->total_points,
                'time_limit' => $assignment->time_limit_minutes,
                'questions' => $assignment->questions,
                'is_past_due' => $assignment->isPastDue(),
                'teacher' => $assignment->teacher?->name,
            ],
            'submission' => $submission ? [
                'id' => $submission->id,
                'content' => $submission->content,
                'answers' => $submission->answers,
                'file_path' => $submission->file_path,
                'score' => $submission->score,
                'max_score' => $submission->max_score,
                'feedback' => $submission->feedback,
                'submitted_at' => $submission->submitted_at?->toISOString(),
                'graded_at' => $submission->graded_at?->toISOString(),
                'letter_grade' => $submission->letterGrade(),
            ] : null,
        ]);
    }

    /**
     * Submit an assignment or test.
     */
    public function submitAssignment(Request $request, Assignment $assignment)
    {
        $student = Auth::user()->studentRecord;
        abort_if(! $student || $assignment->room_id !== $student->room_id, 403);

        // Check if already submitted
        $existing = $assignment->submissions()->where('student_id', $student->id)->first();
        abort_if($existing, 422, 'You have already submitted this assignment.');

        $rules = [];
        if ($assignment->isTest()) {
            $rules['answers'] = 'required|array';
        } else {
            $rules['content'] = 'nullable|string';
            $rules['file'] = 'nullable|file|max:10240'; // 10MB max
        }

        $request->validate($rules);

        $data = [
            'assignment_id' => $assignment->id,
            'student_id' => $student->id,
            'submitted_at' => now(),
            'max_score' => $assignment->total_points,
        ];

        if ($assignment->isTest()) {
            $data['answers'] = $request->answers;
            // Auto-grade MCQ questions
            $score = $this->autoGradeTest($assignment->questions, $request->answers);
            if ($score !== null) {
                $data['score'] = $score;
                $data['graded_at'] = now();
                $data['graded_by'] = $assignment->teacher_id;
            }
        } else {
            $data['content'] = $request->content;
            if ($request->hasFile('file')) {
                $data['file_path'] = $request->file('file')->store('submissions', 'public');
            }
        }

        Submission::create($data);

        return redirect()->back()->with('success', 'Submission received successfully!');
    }

    /**
     * View full grade report.
     */
    public function grades()
    {
        $student = Auth::user()->studentRecord;
        if (! $student) {
            return Inertia::render('Student/Grades', ['grades' => [], 'gpa' => 0, 'subjects' => []]);
        }

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

        // Group by subject for summary
        $subjects = $grades->groupBy('subject')->map(function ($items, $subject) {
            $avgPct = $items->avg('percentage');

            return [
                'subject' => $subject,
                'average' => round($avgPct, 1),
                'letter' => Grade::computeLetterGrade($avgPct, 100),
                'count' => $items->count(),
            ];
        })->values();

        return Inertia::render('Student/Grades', [
            'grades' => $grades,
            'gpa' => $student->gpa(),
            'subjects' => $subjects,
        ]);
    }

    /**
     * Auto-grade MCQ-based tests.
     */
    private function autoGradeTest(?array $questions, array $answers): ?int
    {
        if (! $questions) {
            return null;
        }

        $totalMcq = 0;
        $correct = 0;
        $hasNonMcq = false;

        foreach ($questions as $i => $question) {
            if ($question['type'] === 'mcq') {
                $totalMcq++;
                if (isset($answers[$i]) && $answers[$i] == $question['correct']) {
                    $correct++;
                }
            } else {
                $hasNonMcq = true;
            }
        }

        // If all questions are MCQ, auto-grade. Otherwise return null for manual grading.
        if ($hasNonMcq) {
            return null;
        }
        if ($totalMcq === 0) {
            return null;
        }

        // Scale to total_points
        return $totalMcq > 0 ? round(($correct / $totalMcq) * 100) : 0;
    }
}
