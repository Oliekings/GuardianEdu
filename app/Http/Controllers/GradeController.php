<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Schedule;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class GradeController extends Controller
{
    /**
     * Display grade book.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $schoolId = $user->school_id;

        // Get subjects this teacher handles (from their schedules)
        $subjects = Schedule::where('teacher_id', $user->id)
            ->distinct()
            ->pluck('subject_name');

        $selectedSubject = $request->get('subject', $subjects->first());
        $selectedTerm = $request->get('term', 'Term 1');

        // Get rooms this teacher teaches in
        $rooms = Schedule::where('teacher_id', $user->id)
            ->where('subject_name', $selectedSubject)
            ->distinct()
            ->pluck('room_id');

        // Students in those rooms
        $students = Student::where('school_id', $schoolId)
            ->whereIn('room_id', $rooms)
            ->get()
            ->map(function ($student) use ($selectedSubject, $selectedTerm) {
                $grade = $student->grades()
                    ->where('subject', $selectedSubject)
                    ->where('term', $selectedTerm)
                    ->first();

                return [
                    'id' => $student->id,
                    'name' => $student->full_name,
                    'admission_number' => $student->admission_number,
                    'room_id' => $student->room_id,
                    'grade' => $grade ? [
                        'id' => $grade->id,
                        'score' => $grade->score,
                        'max_score' => $grade->max_score,
                        'letter_grade' => $grade->letter_grade,
                        'percentage' => $grade->percentage(),
                        'remarks' => $grade->remarks,
                    ] : null,
                ];
            });

        return Inertia::render('Teacher/GradeBook', [
            'students' => $students,
            'subjects' => $subjects,
            'selectedSubject' => $selectedSubject,
            'selectedTerm' => $selectedTerm,
            'terms' => ['Term 1', 'Term 2', 'Term 3', 'Midterm', 'Final'],
        ]);
    }

    /**
     * Store or update a grade.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject' => 'required|string',
            'term' => 'required|string',
            'score' => 'required|numeric|min:0',
            'max_score' => 'required|numeric|min:1',
            'remarks' => 'nullable|string',
        ]);

        $letterGrade = Grade::computeLetterGrade($request->score, $request->max_score);
        $student = Student::findOrFail($request->student_id);

        Grade::updateOrCreate(
            [
                'student_id' => $request->student_id,
                'subject' => $request->subject,
                'term' => $request->term,
            ],
            [
                'school_id' => $student->school_id,
                'score' => $request->score,
                'max_score' => $request->max_score,
                'letter_grade' => $letterGrade,
                'teacher_id' => Auth::id(),
                'remarks' => $request->remarks,
            ]
        );

        return redirect()->back()->with('success', 'Grade saved successfully!');
    }
}
