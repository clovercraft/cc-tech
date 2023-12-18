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
            'player_id'     => 'required|string',
            'api_token'     => 'required|exists:servers,api_key'
        ]);

        $player = MinecraftAccount::where('uuid', $request->input('player_id'))->first();

        $event = new MinecraftEvent();
        $event->event_type = $request->input('event_type');
        $event->context = json_decode($request->input('context', '[]'));
        $player->minecraftEvents()->save($event);

        return response()->json(['success' => true, 'record' => json_encode($event->toArray())]);
    }

    public function global_event_hook(Request $request)
    {
        $request->validate([
            'event_type'    => 'required|in:startup,shutdown',
            'api_token'     => 'required|exists:servers,api_key',
        ]);

        $player = MinecraftAccount::first();

        $event = new MinecraftEvent();
        $event->event_type = $request->input('event_type');
        $event->context = [];
        $player->minecraftEvents()->save($event);

        return response()->json(['success' => true]);
    }
}
