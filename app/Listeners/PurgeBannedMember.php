<?php

namespace App\Listeners;

use App\Events\MemberBanned;
use App\Models\Server;
use App\Service\Minecraft\Signals\WhitelistRemove;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class PurgeBannedMember
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MemberBanned $event): void
    {
        $member = $event->member;
        $avatars = $member->minecraftAccounts;

        // mark the member account as banned
        $member->status = 'banned';

        // mark each Minecraft account as banned, and remove from whitelist
        $servers = Server::where('whistelist_active', true)->get();
        foreach ($avatars as $avatar) {
            $avatar->status = 'banned';
            $avatar->save();

            foreach ($servers as $server) {
                WhitelistRemove::make($avatar->name)
                    ->withToken($server->api_key)
                    ->send();
            }
        }

        // log action
        Log::info("Purged user due to ban", [
            'member'    => $member->name,
            'avatars'   => $avatars->pluck('name'),
        ]);
    }
}
