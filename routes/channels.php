<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('bus.{busId}', function ($user, $busId) {
    if (! $user || $user->role !== 'parent') {
        return false;
    }

    // A parent can only track the bus if their child is currently "signed into" that bus.
    return $user->children()
        ->where('current_bus_id', $busId)
        ->exists();
});
