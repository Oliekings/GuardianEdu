<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AssignmentController extends Controller
{
    /**
     * List teacher's assignments.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Assignment::where('teacher_id', $user->id)
            ->withCount('submissions');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $assignments = $query->latest()->get()->map(fn ($a) => [
            'id' => $a->id,
            'title' => $a->title,
            'subject' => $a->subject,
            'type' => $a->type,
            'room_id' => $a->room_id,
            'due_at' => $a->due_at?->toISOString(),
            'due_label' => $a->due_at ? $a->due_at->diffForHumans() : 'No deadline',
            'total_points' => $a->total_points,
            'is_published' => $a->is_published,
            'is_past_due' => $a->isPastDue(),
            'submissions_count' => $a->submissions_count,
            'graded_count' => $a->gradedCount(),
            'time_limit' => $a->time_limit_minutes,
            'created_at' => $a->created_at->toISOString(),
        ]);

        return Inertia::render('Teacher/Assignments/Index', [
            'assignments' => $assignments,
            'filters' => $request->only(['type']),
        ]);
    }

    /**
     * Show create assignment form.
     */
    public function create()
    {
        return Inertia::render('Teacher/Assignments/Create', [
            'rooms' => Schedule::where('teacher_id', Auth::id())
                ->distinct()
                ->pluck('room_id'),
        ]);
    }

    /**
     * Store a new assignment.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subject' => 'required|string|max:100',
            'type' => 'required|in:assignment,test,quiz',
            'room_id' => 'required|string',
            'description' => 'nullable|string',
            'due_at' => 'nullable|date',
            'total_points' => 'required|integer|min:1',
            'time_limit_minutes' => 'nullable|integer|min:1',
            'is_published' => 'boolean',
            'questions' => 'nullable|array',
            'questions.*.type' => 'required_with:questions|in:mcq,short_answer',
            'questions.*.question' => 'required_with:questions|string',
        ]);

        $assignment = Assignment::create([
            'school_id' => Auth::user()->school_id,
            'teacher_id' => Auth::id(),
            'title' => $request->title,
            'subject' => $request->subject,
            'type' => $request->type,
            'room_id' => $request->room_id,
            'description' => $request->description,
            'due_at' => $request->due_at,
            'total_points' => $request->total_points,
            'time_limit_minutes' => $request->time_limit_minutes,
            'is_published' => $request->is_published ?? false,
            'questions' => $request->questions,
        ]);

        return redirect()->route('staff.assignments.show', $assignment->id)
            ->with('success', 'Assignment created successfully!');
    }

    /**
     * View assignment with submissions.
     */
    public function show(Assignment $assignment)
    {
        abort_unless($assignment->teacher_id === Auth::id(), 403);

        $submissions = $assignment->submissions()
            ->with('student:id,first_name,last_name,admission_number')
            ->latest('submitted_at')
            ->get()
            ->map(fn ($s) => [
                'id' => $s->id,
                'student_name' => $s->student?->full_name,
                'admission_number' => $s->student?->admission_number,
                'content' => $s->content,
                'answers' => $s->answers,
                'file_path' => $s->file_path ? asset('storage/'.$s->file_path) : null,
                'score' => $s->score,
                'max_score' => $s->max_score,
                'feedback' => $s->feedback,
                'submitted_at' => $s->submitted_at?->toISOString(),
                'graded_at' => $s->graded_at?->toISOString(),
                'is_graded' => $s->isGraded(),
                'letter_grade' => $s->letterGrade(),
                'percentage' => $s->percentage(),
            ]);

        // Students who haven't submitted yet
        $submittedIds = $assignment->submissions()->pluck('student_id');
        $missing = Student::where('room_id', $assignment->room_id)
            ->where('school_id', $assignment->school_id)
            ->whereNotIn('id', $submittedIds)
            ->get(['id', 'first_name', 'last_name', 'admission_number'])
            ->map(fn ($s) => [
                'id' => $s->id,
                'name' => $s->full_name,
                'admission_number' => $s->admission_number,
            ]);

        return Inertia::render('Teacher/Assignments/Show', [
            'assignment' => [
                'id' => $assignment->id,
                'title' => $assignment->title,
                'subject' => $assignment->subject,
                'type' => $assignment->type,
                'room_id' => $assignment->room_id,
                'description' => $assignment->description,
                'due_at' => $assignment->due_at?->toISOString(),
                'total_points' => $assignment->total_points,
                'time_limit' => $assignment->time_limit_minutes,
                'is_published' => $assignment->is_published,
                'questions' => $assignment->questions,
            ],
            'submissions' => $submissions,
            'missing_students' => $missing,
        ]);
    }

    /**
     * Grade a submission.
     */
    public function gradeSubmission(Request $request, Submission $submission)
    {
        abort_unless($submission->assignment->teacher_id === Auth::id(), 403);

        $request->validate([
            'score' => 'required|integer|min:0',
            'feedback' => 'nullable|string',
        ]);

        $submission->update([
            'score' => $request->score,
            'max_score' => $submission->assignment->total_points,
            'feedback' => $request->feedback,
            'graded_by' => Auth::id(),
            'graded_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Submission graded successfully!');
    }

    /**
     * Toggle publish status.
     */
    public function togglePublish(Assignment $assignment)
    {
        abort_unless($assignment->teacher_id === Auth::id(), 403);

        $assignment->update(['is_published' => ! $assignment->is_published]);

        return redirect()->back()->with('success',
            $assignment->is_published ? 'Assignment published!' : 'Assignment unpublished.'
        );
    }
}
