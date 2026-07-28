<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\School;
use App\Models\Assignment;
use App\Models\Announcement;
use App\Models\BehavioralLog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class AdminPortalController extends Controller
{
    public function index()
    {
        $schoolId = Auth::user()->school_id;

        $stats = [
            'total_students' => Student::where('school_id', $schoolId)->count(),
            'total_staff' => User::where('school_id', $schoolId)->where('role', 'staff')->count(),
            'total_parents' => User::where('school_id', $schoolId)->where('role', 'parent')->count(),
            'total_users' => User::where('school_id', $schoolId)->count(),
            'total_assignments' => Assignment::where('school_id', $schoolId)->count(),
            'pending_submissions' => \App\Models\Submission::whereHas('assignment', fn ($q) => $q->where('school_id', $schoolId))
                ->whereNull('graded_at')->count(),
            'attendance_rate' => '94.2%', // Placeholder — in production computed from rfid_taps
            'system_status' => 'Optimum',
        ];

        // Recent announcements
        $announcements = Announcement::where('school_id', $schoolId)
            ->with('author:id,name')
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn ($a) => [
                'id' => $a->id,
                'title' => $a->title,
                'content' => $a->content,
                'target_role' => $a->target_role,
                'author' => $a->author?->name,
                'created_at' => $a->created_at->diffForHumans(),
            ]);

        // Recent activity (behavioral logs)
        $recentActivity = BehavioralLog::whereHas('student', fn ($q) => $q->where('school_id', $schoolId))
            ->with(['student:id,first_name,last_name', 'teacher:id,name'])
            ->latest()
            ->limit(8)
            ->get()
            ->map(fn ($b) => [
                'student' => $b->student?->full_name,
                'teacher' => $b->teacher?->name,
                'type' => $b->type,
                'category' => $b->category,
                'points' => $b->points,
                'date' => $b->created_at->diffForHumans(),
            ]);

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'announcements' => $announcements,
            'recentActivity' => $recentActivity,
        ]);
    }
}
