<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

use App\Models\FeeGroup;

class StudentManagementController extends Controller
{
    /**
     * List all students.
     */
    public function index(Request $request)
    {
        $schoolId = Auth::user()->getScopedSchoolId();

        $query = Student::where('school_id', $schoolId)
            ->with(['parents:id,name,email', 'user:id,name,email', 'feeGroup:id,name']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('admission_number', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        $students = $query->orderBy('last_name')->get()->map(fn ($s) => [
            'id' => $s->id,
            'admission_number' => $s->admission_number,
            'full_name' => $s->full_name,
            'email' => $s->email,
            'category' => $s->category,
            'room_id' => $s->room_id,
            'fee_group' => $s->feeGroup?->name,
            'is_active' => $s->is_active,
            'user_email' => $s->user?->email,
        ]);

        $rooms = Student::where('school_id', $schoolId)
            ->whereNotNull('room_id')
            ->distinct()
            ->pluck('room_id');

        return Inertia::render('Admin/Students/Index', [
            'students' => $students,
            'rooms' => $rooms,
            'filters' => $request->only(['search', 'room_id']),
        ]);
    }

    public function create()
    {
        $schoolId = Auth::user()->getScopedSchoolId();

        return Inertia::render('Admin/Students/Create', [
            'parentUsers' => User::where('school_id', $schoolId)->where('role', 'parent')->get(['id', 'name', 'email']),
            'studentUsers' => User::where('school_id', $schoolId)->where('role', 'student')
                ->whereDoesntHave('studentRecord')->get(['id', 'name', 'email']),
            'feeGroups' => FeeGroup::where('school_id', $schoolId)->get(['id', 'name']),
        ]);
    }

    public function store(Request $request)
    {
        $schoolId = Auth::user()->getScopedSchoolId();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'admission_number' => 'required|string|unique:students,admission_number',
            'guardian_name' => 'required|string',
            'guardian_phone' => 'required|string',
            'fee_group_id' => 'nullable|exists:fee_groups,id',
        ]);

        $student = Student::create(array_merge($request->all(), ['school_id' => $schoolId]));

        if ($request->filled('parent_ids')) {
            $student->parents()->attach($request->parent_ids);
        }

        return redirect()->route('admin.students.index')->with('success', 'Student admitted successfully!');
    }

    public function edit(Student $student)
    {
        $schoolId = Auth::user()->getScopedSchoolId();
        abort_unless($student->school_id === $schoolId, 403);

        return Inertia::render('Admin/Students/Edit', [
            'student' => $student->load('parents:id'),
            'parentUsers' => User::where('school_id', $schoolId)->where('role', 'parent')->get(['id', 'name', 'email']),
            'studentUsers' => User::where('school_id', $schoolId)->where('role', 'student')
                ->where(function ($q) use ($student) {
                    $q->whereDoesntHave('studentRecord')->orWhere('id', $student->user_id);
                })->get(['id', 'name', 'email']),
            'feeGroups' => FeeGroup::where('school_id', $schoolId)->get(['id', 'name']),
        ]);
    }

    public function update(Request $request, Student $student)
    {
        $schoolId = Auth::user()->getScopedSchoolId();
        abort_unless($student->school_id === $schoolId, 403);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'admission_number' => 'required|string|unique:students,admission_number,' . $student->id,
            'guardian_name' => 'required|string',
            'guardian_phone' => 'required|string',
            'fee_group_id' => 'nullable|exists:fee_groups,id',
        ]);

        $student->update($request->all());

        if ($request->has('parent_ids')) {
            $student->parents()->sync($request->parent_ids);
        }

        return redirect()->route('admin.students.index')->with('success', 'Student profile updated!');
    }

    /**
     * Delete a student.
     */
    public function destroy(Student $student)
    {
        abort_unless($student->school_id === Auth::user()->school_id, 403);
        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Student removed successfully.');
    }
}
