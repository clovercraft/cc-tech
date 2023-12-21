<?php

namespace App\Jobs;

use App\Models\MinecraftAccount;
use App\Models\Server;
use App\Service\Minecraft\Signals\WhitelistAdd;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExportWhitelist implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $server;

    /**
     * Create a new job instance.
     */
    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $token = $this->server->api_key;

        $players = MinecraftAccount::where('status', 'active')->get();
        foreach ($players as $player) {
            WhitelistAdd::make($player->name)
                ->withToken($token)
                ->send();
            $player->whitelisted_at = now();
            $player->save();
        }
    }
}
