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
            // verify member
            $account = $player->name;
            if (!Minecraft::verifyAccount($account)) {
                Log::warning("Minecraft user failed verification during export", ['account' => $player]);
                continue;
            }

            // send whitelist signal
            WhitelistAdd::make($player->name)
                ->withToken($token)
                ->send();

            $player->whitelisted_at = now();
            $player->status = 'whitelisted';
            $player->save();
        }
    }
}
