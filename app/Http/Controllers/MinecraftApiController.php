<?php

namespace App\Http\Controllers;

use App\Models\MinecraftEvent;
use App\Models\Server;
use App\Models\MinecraftAccount;
use Illuminate\Http\Request;

class MinecraftApiController extends Controller
{
    public function event_hook(Request $request)
    {
        $request->validate([
            'event_type'    => 'required|string',
            'player_id'     => 'string',
            'api_token'     => 'required|exists:servers,api_key'
        ]);

        $server = Server::where('api_key', $request->input('api_token'))->first();

        $event = new MinecraftEvent();
        $event->event_type = $request->input('event_type');
        $event->context = $request->input('context', '[]');
        $server->events()->save($event);

        if ($request->has('player_id')) {
            $player = MinecraftAccount::where('uuid', $request->input('player_id'))->first();
            $event->minecraftAccount()->associate($player);
            $event->save();
        }

        return response()->json(['success' => true, 'record' => json_encode($event->toArray())]);
    }
}
