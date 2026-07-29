<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Rooms this teacher manages
        $rooms = Schedule::where('teacher_id', $user->id)
            ->distinct()
            ->pluck('room_id');

        $selectedRoom = $request->query('room_id', $rooms->first() ?? '');
        $date = $request->query('date', today()->toDateString());

        $students = collect();

        if ($selectedRoom) {
            $students = Student::where('school_id', $user->getScopedSchoolId())
                ->where('room_id', $selectedRoom)
                ->get()
                ->map(function ($s) use ($date) {
                    $attendance = Attendance::where('student_id', $s->id)
                        ->where('date', $date)
                        ->first();

                    return [
                        'id' => $s->id,
                        'name' => $s->full_name,
                        'admission_number' => $s->admission_number,
                        'image' => $s->student_image ? "/storage/{$s->student_image}" : null,
                        'status' => $attendance?->status ?? 'Present', // Default to present for speed
                        'remarks' => $attendance?->remarks ?? '',
                    ];
                });
        }

        return Inertia::render('Attendance/Index', [
            'rooms' => $rooms,
            'students' => $students,
            'filters' => [
                'room_id' => $selectedRoom,
                'date' => $date,
            ],
            'stats' => [
                'total' => $students->count(),
                'present' => $students->where('status', 'Present')->count(),
                'absent' => $students->where('status', 'Absent')->count(),
                'late' => $students->where('status', 'Late')->count(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required',
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:students,id',
            'attendance.*.status' => 'required|in:Present,Absent,Late,Half Day',
        ]);

        $user = Auth::user();
        $schoolId = $user->getScopedSchoolId();

        foreach ($request->attendance as $record) {
            Attendance::updateOrCreate(
                [
                    'school_id' => $schoolId,
                    'student_id' => $record['student_id'],
                    'date' => $request->date,
                ],
                [
                    'status' => $record['status'],
                    'remarks' => $record['remarks'] ?? null,
                    'marked_by' => $user->id,
                ]
            );
        }

        return redirect()->back()->with('success', 'Attendance marked successfully.');
    }
}
