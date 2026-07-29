<?php

namespace App\Events;

use App\Models\RfidTap;
use App\Models\Student;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StudentTappedIn implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $student;

    public $tap;

    /**
     * Create a new event instance.
     */
    public function __construct(Student $student, RfidTap $tap)
    {
        $this->student = $student;
        $this->tap = $tap;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        // Broadcast to a private channel for the bus
        return [
            new PrivateChannel('bus.'.$this->tap->bus_id),
        ];
    }
}
