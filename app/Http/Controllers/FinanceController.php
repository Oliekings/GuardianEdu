<?php

namespace App\Http\Controllers;

use App\Models\FeeGroup;
use App\Models\FeeType;
use App\Models\FeeMaster;
use App\Models\FeeDeposit;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class FinanceController extends Controller
{
    public function feeGroupIndex()
    {
        $schoolId = Auth::user()->getScopedSchoolId();
        $groups = FeeGroup::where('school_id', $schoolId)->get();
        return Inertia::render('Accountant/Fees/Groups', ['groups' => $groups]);
    }

    public function storeGroup(Request $request)
    {
        $request->validate(['name' => 'required|string']);
        FeeGroup::create([
            'school_id' => Auth::user()->getScopedSchoolId(),
            'name' => $request->name,
            'description' => $request->description
        ]);
        return redirect()->back()->with('success', 'Fee group created.');
    }

    public function feeTypeIndex()
    {
        $schoolId = Auth::user()->getScopedSchoolId();
        $types = FeeType::where('school_id', $schoolId)->get();
        return Inertia::render('Accountant/Fees/Types', ['types' => $types]);
    }

    public function storeType(Request $request)
    {
        $request->validate(['name' => 'required', 'code' => 'required']);
        FeeType::create([
            'school_id' => Auth::user()->getScopedSchoolId(),
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description
        ]);
        return redirect()->back()->with('success', 'Fee type created.');
    }

    public function feeMasterIndex()
    {
        $schoolId = Auth::user()->getScopedSchoolId();
        $masters = FeeMaster::where('school_id', $schoolId)->with(['group', 'type'])->get();
        return Inertia::render('Accountant/Fees/Masters', [
            'masters' => $masters,
            'groups' => FeeGroup::where('school_id', $schoolId)->get(),
            'types' => FeeType::where('school_id', $schoolId)->get(),
        ]);
    }

    public function storeMaster(Request $request)
    {
        $request->validate([
            'fee_group_id' => 'required|exists:fee_groups,id',
            'fee_type_id' => 'required|exists:fee_types,id',
            'amount' => 'required|numeric',
        ]);
        FeeMaster::create([
            'school_id' => Auth::user()->getScopedSchoolId(),
            'fee_group_id' => $request->fee_group_id,
            'fee_type_id' => $request->fee_type_id,
            'amount' => $request->amount,
            'due_date' => $request->due_date
        ]);
        return redirect()->back()->with('success', 'Fee master configured.');
    }

    public function collectionIndex(Request $request)
    {
        $schoolId = Auth::user()->getScopedSchoolId();
        $students = collect();

        if ($request->filled('search')) {
            $students = Student::where('school_id', $schoolId)
                ->where('admission_number', 'like', "%{$request->search}%")
                ->orWhere('first_name', 'like', "%{$request->search}%")
                ->get();
        }

        return Inertia::render('Accountant/Fees/Collect', [
            'students' => $students,
            'filters' => $request->only(['search'])
        ]);
    }

    public function showCollection(Student $student)
    {
        $schoolId = Auth::user()->getScopedSchoolId();
        abort_unless($student->school_id === $schoolId, 403);

        $student->load(['room', 'feeGroup.masters.type']);
        
        // Fetch all deposits made by this student
        $deposits = FeeDeposit::where('student_id', $student->id)->with('master.type')->get();

        return Inertia::render('Accountant/Fees/StudentCollection', [
            'student' => $student,
            'feeMasters' => $student->feeGroup?->masters ?? [],
            'deposits' => $deposits,
        ]);
    }

    public function storeDeposit(Request $request, Student $student)
    {
        $schoolId = Auth::user()->getScopedSchoolId();
        abort_unless($student->school_id === $schoolId, 403);

        $request->validate([
            'fee_master_id' => 'required|exists:fee_masters,id',
            'amount_paid' => 'required|numeric|min:0',
            'payment_mode' => 'required|string',
        ]);

        FeeDeposit::create([
            'school_id' => $schoolId,
            'student_id' => $student->id,
            'fee_master_id' => $request->fee_master_id,
            'amount_paid' => $request->amount_paid,
            'payment_mode' => $request->payment_mode,
            'deposit_date' => now(),
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Payment recorded successfully.');
    }
}
