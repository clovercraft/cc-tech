<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SmpSignal implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $signalName = '';
    public array $signalArgs = [];

    /**
     * Create a new event instance.
     */
    public function __construct(string $signalName, array $signalArgs)
    {
        $this->signalName = $signalName;
        $this->signalArgs = $signalArgs;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channel = env('SMP_RELAY_CHANNEL');
        return [
            new PrivateChannel($channel),
        ];
    }
}
