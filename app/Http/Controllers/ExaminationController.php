<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamSchedule;
use App\Models\ExamMark;
use App\Models\GradeScale;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ExaminationController extends Controller
{
    // Exam Management
    public function examIndex()
    {
        $schoolId = Auth::user()->getScopedSchoolId();
        $exams = Exam::where('school_id', $schoolId)->get();
        return Inertia::render('Admin/Academics/Exam/Index', ['exams' => $exams]);
    }

    public function storeExam(Request $request)
    {
        $request->validate(['name' => 'required|string', 'session' => 'required|string']);
        Exam::create(array_merge($request->all(), [
            'school_id' => Auth::user()->getScopedSchoolId()
        ]));
        return redirect()->back()->with('success', 'Exam created.');
    }

    // Grading Scales
    public function gradeIndex()
    {
        $schoolId = Auth::user()->getScopedSchoolId();
        $scales = GradeScale::where('school_id', $schoolId)->orderBy('min_score', 'desc')->get();
        return Inertia::render('Admin/Academics/Exam/Grading', ['scales' => $scales]);
    }

    public function storeGrade(Request $request)
    {
        $request->validate([
            'grade_name' => 'required|string',
            'min_score' => 'required|numeric',
            'max_score' => 'required|numeric',
        ]);
        GradeScale::create(array_merge($request->all(), [
            'school_id' => Auth::user()->getScopedSchoolId()
        ]));
        return redirect()->back()->with('success', 'Grade scale updated.');
    }

    // Scheduling
    public function scheduleIndex(Exam $exam)
    {
        $exam->load('schedules');
        return Inertia::render('Admin/Academics/Exam/Schedule', ['exam' => $exam]);
    }

    public function storeSchedule(Request $request, Exam $exam)
    {
        $request->validate(['subject_name' => 'required', 'date' => 'required|date']);
        $exam->schedules()->create($request->all());
        return redirect()->back()->with('success', 'Schedule added.');
    }

    // Marks Entry (Teacher)
    public function marksIndex(ExamSchedule $schedule)
    {
        $schoolId = Auth::user()->getScopedSchoolId();
        $students = Student::where('school_id', $schoolId)->with('user')->get();
        $schedule->load('marks');
        
        return Inertia::render('Teacher/Marks/Entry', [
            'schedule' => $schedule,
            'students' => $students
        ]);
    }

    public function storeMark(Request $request)
    {
        $request->validate([
            'marks' => 'required|array',
            'exam_schedule_id' => 'required|exists:exam_schedules,id'
        ]);

        foreach ($request->marks as $studentId => $score) {
            ExamMark::updateOrCreate(
                ['exam_schedule_id' => $request->exam_schedule_id, 'student_id' => $studentId],
                ['marks_obtained' => $score]
            );
        }

        return redirect()->back()->with('success', 'Marks synced.');
    }

    // Results (Student/Parent)
    public function resultsIndex()
    {
        $student = Auth::user()->student;
        if (!$student) return redirect()->back()->with('error', 'Student not found.');

        $results = Exam::where('school_id', $student->school_id)
            ->with(['schedules' => function($q) use ($student) {
                $q->with(['marks' => function($mq) use ($student) {
                    $mq->where('student_id', $student->id);
                }]);
            }])
            ->get();

        foreach ($results as $exam) {
            $totalObtained = 0;
            $totalMax = 0;
            foreach ($exam->schedules as $schedule) {
                $mark = $schedule->marks->first();
                $totalObtained += $mark ? (float)$mark->marks_obtained : 0;
                $totalMax += $schedule->max_marks;
            }
            $percentage = $totalMax > 0 ? ($totalObtained / $totalMax) * 100 : 0;
            $exam->total_obtained = $totalObtained;
            $exam->total_max = $totalMax;
            $exam->percentage = round($percentage, 2);
            $exam->grade = $this->calculateGrade($percentage, $student->school_id);
        }

        return Inertia::render('Student/Result/Show', ['results' => $results]);
    }

    public function downloadMarksheet(Exam $exam)
    {
        $student = Auth::user()->student;
        $exam->load(['schedules' => function($q) use ($student) {
            $q->with(['marks' => function($mq) use ($student) {
                $mq->where('student_id', $student->id);
            }]);
        }]);

        $totalObtained = 0;
        $totalMax = 0;
        foreach ($exam->schedules as $schedule) {
            $mark = $schedule->marks->first();
            $totalObtained += $mark ? (float)$mark->marks_obtained : 0;
            $totalMax += $schedule->max_marks;
        }
        $percentage = $totalMax > 0 ? ($totalObtained / $totalMax) * 100 : 0;
        $grade = $this->calculateGrade($percentage, $student->school_id);

        return \Spatie\LaravelPdf\Facades\Pdf::view('pdf.marksheet', [
            'exam' => $exam,
            'student' => $student,
            'school' => Auth::user()->school,
            'totalObtained' => $totalObtained,
            'totalMax' => $totalMax,
            'percentage' => round($percentage, 2),
            'grade' => $grade,
        ])
        ->name("Marksheet-{$exam->name}.pdf");
    }

    private function calculateGrade($percentage, $schoolId)
    {
        $scale = GradeScale::where('school_id', $schoolId)
            ->where('min_score', '<=', $percentage)
            ->where('max_score', '>=', $percentage)
            ->first();
        
        return $scale ? $scale->grade_name : 'N/A';
    }
}
