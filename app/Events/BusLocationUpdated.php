<?php

namespace App\Events;

use App\Models\BusFleet;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BusLocationUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $bus;

    /**
     * Create a new event instance.
     */
    public function __construct(BusFleet $bus)
    {
        $this->bus = $bus;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('fleet-telemetry'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'BusLocationUpdated';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->bus->id,
            'vehicle_number' => $this->bus->vehicle_number,
            'current_lat' => (float) $this->bus->current_lat,
            'current_lng' => (float) $this->bus->current_lng,
            'heading' => (float) $this->bus->heading,
            'status' => $this->bus->status,
        ];
    }
}
