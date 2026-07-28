<?php

namespace App\Http\Middleware;

use App\Models\Student;
use App\Models\Schedule;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeCameraAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user || $user->role !== 'parent') {
            return response()->json(['message' => 'Unauthorized access.'], 403);
        }

        $cameraId = $request->route('camera_id');
        $now = now();

        // 1. Get user's children student IDs
        $studentIds = $user->children()->pluck('students.id');

        if ($studentIds->isEmpty()) {
            return response()->json(['message' => 'No students associated with this account.'], 403);
        }

        // 2. Check if any child is currently scheduled in a class tied to this camera
        $isAuthorized = Schedule::whereIn('room_id', function($query) use ($studentIds) {
                // Assuming student has a 'room_id' for mapping to schedule rooms
                $query->select('room_id')->from('students')->whereIn('id', $studentIds);
            })
            ->where('camera_feed_id', $cameraId)
            ->where('day_of_week', $now->dayOfWeek)
            ->whereTime('start_time', '<=', $now->toTimeString())
            ->whereTime('end_time', '>=', $now->toTimeString())
            ->exists();

        if (!$isAuthorized) {
            return response()->json(['message' => 'Live feed not authorized at this time.'], 403);
        }

        return $next($request);
    }
}
