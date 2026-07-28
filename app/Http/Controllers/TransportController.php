<?php

namespace App\Http\Controllers;

use App\Models\BusFleet;
use App\Models\TransportRoute;
use App\Models\TransportAssignment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TransportController extends Controller
{
    public function fleetIndex()
    {
        $schoolId = Auth::user()->getScopedSchoolId();
        $fleet = BusFleet::where('school_id', $schoolId)->get();
        return Inertia::render('Admin/Transport/Fleet', ['fleet' => $fleet]);
    }

    public function storeFleet(Request $request)
    {
        $request->validate(['vehicle_number' => 'required|string|unique:bus_fleets,vehicle_number']);
        BusFleet::create(array_merge($request->all(), [
            'school_id' => Auth::user()->getScopedSchoolId()
        ]));
        return redirect()->back()->with('success', 'Vehicle registered.');
    }

    public function routeIndex()
    {
        $schoolId = Auth::user()->getScopedSchoolId();
        $routes = TransportRoute::where('school_id', $schoolId)->get();
        return Inertia::render('Admin/Transport/Routes', ['routes' => $routes]);
    }

    public function storeRoute(Request $request)
    {
        $request->validate(['name' => 'required|string', 'monthly_fee' => 'required|numeric']);
        TransportRoute::create(array_merge($request->all(), [
            'school_id' => Auth::user()->getScopedSchoolId()
        ]));
        return redirect()->back()->with('success', 'Route created.');
    }

    public function assignIndex()
    {
        $schoolId = Auth::user()->getScopedSchoolId();
        $assignments = TransportAssignment::where('school_id', $schoolId)
            ->with(['student.user', 'route', 'vehicle'])
            ->get();
            
        return Inertia::render('Admin/Transport/Assign', [
            'assignments' => $assignments,
            'students' => Student::where('school_id', $schoolId)->with('user')->get(),
            'routes' => TransportRoute::where('school_id', $schoolId)->get(),
            'fleet' => BusFleet::where('school_id', $schoolId)->get()
        ]);
    }

    public function storeAssignment(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'transport_route_id' => 'required|exists:transport_routes,id',
            'bus_fleet_id' => 'required|exists:bus_fleets,id',
        ]);

        TransportAssignment::updateOrCreate(
            ['student_id' => $request->student_id],
            array_merge($request->all(), ['school_id' => Auth::user()->getScopedSchoolId()])
        );

        return redirect()->back()->with('success', 'Transport assigned.');
    }
}
