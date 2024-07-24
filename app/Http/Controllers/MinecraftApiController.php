<?php

namespace App\Http\Controllers;

use App\Models\MinecraftEvent;
use App\Models\Server;
use App\Models\MinecraftAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MinecraftApiController extends Controller
{
    public function event_hook(Request $request)
    {
        $request->validate([
            'event'    => 'required|string',
            'player'        => 'string',
            'token'     => 'required|exists:servers,api_key'
        ]);

        $server = Server::where('api_key', $request->input('token'))->first();

        $event = new MinecraftEvent();
        $event->event_type = $request->input('event');
        $event->context = $request->input('context', '[]');
        $server->events()->save($event);

        if ($request->has('player')) {
            $player = MinecraftAccount::where('name', $request->input('player'))->first();
            if ($player) {
                $event->minecraftAccount()->associate($player);
                $event->save();
            }
        }

        Log::info("minecraft event", ['event' => $event]);

        return response()->json(['success' => true, 'record' => json_encode($event->toArray())]);
    }
}
