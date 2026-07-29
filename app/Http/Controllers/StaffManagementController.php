<?php

namespace App\Http\Controllers;

use App\Models\StaffProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class StaffManagementController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = Auth::user()->getScopedSchoolId();

        $query = StaffProfile::where('school_id', $schoolId)
            ->with('user:id,name,email,role');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('staff_id', 'like', "%{$search}%")
                    ->orWhere('designation', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $staff = $query->get()->map(fn ($s) => [
            'id' => $s->id,
            'staff_id' => $s->staff_id,
            'name' => $s->user?->name,
            'email' => $s->user?->email,
            'role' => $s->user?->role,
            'designation' => $s->designation,
            'phone' => $s->phone,
            'joining_date' => $s->joining_date,
        ]);

        return Inertia::render('Admin/Staff/Index', [
            'staff' => $staff,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create()
    {
        $schoolId = Auth::user()->getScopedSchoolId();

        // Find users who have staff roles but no profile yet
        $availableUsers = User::where('school_id', $schoolId)
            ->whereIn('role', ['teacher', 'accountant', 'librarian', 'receptionist', 'staff'])
            ->whereDoesntHave('staffProfile')
            ->get(['id', 'name', 'email', 'role']);

        return Inertia::render('Admin/Staff/Create', [
            'availableUsers' => $availableUsers,
        ]);
    }

    public function store(Request $request)
    {
        $schoolId = Auth::user()->getScopedSchoolId();

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'staff_id' => 'required|string|unique:staff_profiles,staff_id',
            'designation' => 'required|string',
            'joining_date' => 'required|date',
            'basic_salary' => 'nullable|numeric',
        ]);

        StaffProfile::create(array_merge($request->all(), ['school_id' => $schoolId]));

        return redirect()->route('admin.staff.index')->with('success', 'Staff profile onboarding complete!');
    }

    public function edit(StaffProfile $staff)
    {
        $schoolId = Auth::user()->getScopedSchoolId();
        abort_unless($staff->school_id === $schoolId, 403);

        return Inertia::render('Admin/Staff/Edit', [
            'staff' => $staff->load('user:id,name,email,role'),
        ]);
    }

    public function update(Request $request, StaffProfile $staff)
    {
        $schoolId = Auth::user()->getScopedSchoolId();
        abort_unless($staff->school_id === $schoolId, 403);

        $request->validate([
            'staff_id' => 'required|string|unique:staff_profiles,staff_id,'.$staff->id,
            'designation' => 'required|string',
        ]);

        $staff->update($request->all());

        return redirect()->route('admin.staff.index')->with('success', 'Staff profile updated.');
    }

    public function destroy(StaffProfile $staff)
    {
        $staff->delete();

        return redirect()->route('admin.staff.index')->with('success', 'Staff profile removed.');
    }
}
