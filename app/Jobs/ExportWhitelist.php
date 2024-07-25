<?php

namespace App\Jobs;

use App\Facades\Minecraft;
use App\Models\MinecraftAccount;
use App\Models\Server;
use App\Service\Minecraft\Signals\WhitelistAdd;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ExportWhitelist implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $server;
    public $player;

    /**
     * Create a new job instance.
     */
    public function __construct(Server $server, MinecraftAccount $player)
    {
        $this->server = $server;
        $this->player = $player;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $token = $this->server->api_key;
        $player = $this->player;

        // verify member
        $account = $player->name;
        if (!Minecraft::verifyAccount($account)) {
            Log::warning("Minecraft user failed verification during export", ['account' => $player]);
            return;
        }

        // send whitelist signal
        WhitelistAdd::make($player->name)
            ->withToken($token)
            ->send();

        $player->whitelisted_at = now();
        $player->save();
    }
}
