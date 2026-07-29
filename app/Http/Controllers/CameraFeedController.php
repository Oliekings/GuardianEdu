<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;

class CameraFeedController extends Controller
{
    /**
     * List all active classroom streams the user is authorized to view.
     */
    public function index(Request $request)
    {
        $feeds = Classroom::whereNotNull('stream_url')->get();

        // Filter using the Classroom policy
        $authorized = $feeds->filter(function (Classroom $room) {
            return Gate::allows('view', $room);
        })->values();

        return Response::json($authorized->map(function (Classroom $room) {
            return [
                'id' => $room->id,
                'display_name' => $room->name,
                'room_id' => $room->name,
                'playback_url' => $room->stream_url,
            ];
        }));
    }

    /**
     * Show a single classroom stream (if authorized).
     */
    public function show(Classroom $cameraFeed)
    {
        Gate::authorize('view', $cameraFeed);

        return Response::json([
            'id' => $cameraFeed->id,
            'display_name' => $cameraFeed->name,
            'room_id' => $cameraFeed->name,
            'playback_url' => $cameraFeed->stream_url,
        ]);
    }
}
