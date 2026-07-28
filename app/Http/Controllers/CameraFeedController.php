<?php

namespace App\Http\Controllers;

use App\Models\CameraFeed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CameraFeedController extends Controller
{
    /**
     * Display a listing of authorized camera feeds.
     */
    public function index(Request $request)
    {
        $feeds = CameraFeed::where('is_active', true)->get();
        
        // Filter feeds to only those the user is authorized to view
        $authorizedFeeds = $feeds->filter(function ($feed) {
            return Gate::allows('view', $feed);
        })->values();

        return response()->json($authorizedFeeds);
    }

    /**
     * Display the specified authorized camera feed.
     */
    public function show(CameraFeed $cameraFeed)
    {
        Gate::authorize('view', $cameraFeed);

        return response()->json($cameraFeed);
    }
}
