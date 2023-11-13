<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ItineraryEnriched
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $itinerary;

    /**
     * Create a new event instance.
     */
    public function __construct($itinerary)
    {
        $this->itinerary = $itinerary;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('itinerary.' . $this->itinerary->id)
        ];
    }
}
