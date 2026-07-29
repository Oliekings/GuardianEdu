<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AnnouncementController extends Controller
{
    public function index()
    {
        $schoolId = Auth::user()->getScopedSchoolId();
        $announcements = Announcement::where('school_id', $schoolId)
            ->with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Admin/Communication/Announcements', [
            'announcements' => $announcements,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target_role' => 'required|in:all,teacher,parent,student',
        ]);

        Announcement::create([
            'school_id' => Auth::user()->getScopedSchoolId(),
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'target_role' => $request->target_role,
            'expires_at' => $request->expires_at,
        ]);

        return redirect()->back()->with('success', 'Announcement published.');
    }

    public function destroy(Announcement $announcement)
    {
        abort_unless($announcement->school_id === Auth::user()->getScopedSchoolId(), 403);
        $announcement->delete();

        return redirect()->back()->with('success', 'Announcement removed.');
    }
}
