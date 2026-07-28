<?php

namespace App\Services;

use App\Models\Student;
use App\Models\RfidTap;
use App\Events\StudentTappedIn;
use Illuminate\Support\Facades\DB;

class RfidService
{
    /**
     * Handle a student RFID tap action.
     *
     * @param string $rfidToken
     * @param int $busId
     * @param string $action ('tap_in', 'tap_out')
     * @return RfidTap
     */
    public function handleTap(string $rfidToken, int $busId, string $action): RfidTap
    {
        return DB::transaction(function () use ($rfidToken, $busId, $action) {
            $student = Student::where('rfid_token', $rfidToken)->firstOrFail();

            // 1. Record the tap log
            $tap = RfidTap::create([
                'student_id' => $student->id,
                'bus_id' => $busId,
                'action' => $action,
                'tapped_at' => now(),
            ]);

            // 2. Update student state
            if ($action === 'tap_in') {
                $student->update(['current_bus_id' => $busId]);
            } else {
                $student->update(['current_bus_id' => null]);
            }

            // 3. Broadcast real-time event for parent app
            event(new StudentTappedIn($student, $tap));

            return $tap;
        });
    }
}
