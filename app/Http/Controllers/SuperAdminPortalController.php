<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class SuperAdminPortalController extends Controller
{
    /**
     * List all school branches for management.
     */
    public function index()
    {
        $schools = School::withCount(['students', 'users'])->get();

        return Inertia::render('SuperAdmin/Schools/Index', [
            'schools' => $schools,
            'activeSchoolId' => Session::get('active_school_id'),
        ]);
    }

    /**
     * Switch the active branch context for the Super Admin.
     */
    public function switchSchool(Request $request, School $school)
    {
        Session::put('active_school_id', $school->id);

        return redirect()->route('dashboard')
            ->with('success', "Switched to branch: {$school->name}");
    }
}
