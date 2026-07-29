<?php

namespace App\Http\Controllers;

use App\Models\BehavioralLog;
use App\Models\Schedule;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BehavioralController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $schoolId = $user->school_id;

        // Available rooms based on teacher's schedule
        $rooms = Schedule::where('teacher_id', $user->id)
            ->distinct()
            ->pluck('room_id');

        // Get filter inputs
        $roomId = $request->query('room_id', $rooms->first() ?? '');

        $students = Student::where('school_id', $schoolId)
            ->when($roomId, fn ($q) => $q->where('room_id', $roomId))
            ->whereIn('room_id', $rooms)
            ->get()
            ->map(fn ($s) => [
                'id' => $s->id,
                'name' => $s->full_name,
                'admission_number' => $s->admission_number,
                'room_id' => $s->room_id,
            ]);

        $recentLogs = BehavioralLog::where('teacher_id', $user->id)
            ->with('student:id,first_name,last_name')
            ->latest()
            ->limit(15)
            ->get()
            ->map(fn ($l) => [
                'id' => $l->id,
                'student' => $l->student->full_name ?? 'Unknown',
                'type' => $l->type,
                'category' => $l->category,
                'points' => $l->points,
                'description' => $l->description,
                'date' => $l->created_at->diffForHumans(),
            ]);

        return Inertia::render('Teacher/Behavioral/Index', [
            'students' => $students,
            'rooms' => $rooms,
            'recentLogs' => $recentLogs,
            'filters' => ['room_id' => $roomId],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'type' => 'required|in:kudos,incident',
            'category' => 'required|string',
            'points' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        $validated['teacher_id'] = Auth::id();

        // Ensure incidents are negative and kudos are positive
        if ($validated['type'] === 'incident' && $validated['points'] > 0) {
            $validated['points'] = -abs($validated['points']);
        } elseif ($validated['type'] === 'kudos') {
            $validated['points'] = abs($validated['points']);
        }

        BehavioralLog::create($validated);

        return back()->with('success', 'Behavioral log added successfully.');
    }
}
