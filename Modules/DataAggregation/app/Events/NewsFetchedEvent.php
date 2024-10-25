<?php

namespace Modules\DataAggregation\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewsFetchedEvent
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */

    public function __construct(public string $newsData)
    {
    }

}
