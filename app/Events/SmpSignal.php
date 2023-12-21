<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SmpSignal implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $token = '';
    public string $name = '';
    public array $args = [];

    /**
     * Create a new event instance.
     */
    public function __construct(string $signalName, array $signalArgs, string $token)
    {
        $this->token = $token;
        $this->name = $signalName;
        $this->args = $signalArgs;
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
            new Channel($channel),
        ];
    }

    public function broadcastAs(): string
    {
        return 'smpSignal';
    }
}
