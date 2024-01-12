<?php

namespace App\Jobs;

use App\Facades\Discord;
use App\Models\MinecraftAccount;
use App\Models\Server;
use App\Service\Minecraft\Signals\WhitelistAdd;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MinecraftAccountCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $account;

    /**
     * Create a new job instance.
     */
    public function __construct(MinecraftAccount $account)
    {
        $this->account = $account;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $activated = false;
        foreach ($this->getActiveWhitelists() as $server) {
            WhitelistAdd::make($this->account->name)
                ->withToken($server->api_key)
                ->send();
            $activated = true;
        }
        if ($activated) {
            $member = $this->account->member->discord_id;
            $message = sprintf("<@%s>, you are now whitelisted!", $member);
            Discord::send_discord_message($message);
        }
    }

    public function getActiveWhitelists()
    {
        return Server::where('whitelist_active', true)->get();
    }
}
