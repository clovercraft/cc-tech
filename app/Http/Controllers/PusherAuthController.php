<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Http\Request;

class PusherAuthController extends Controller
{

    public function pusher_auth(Request $request)
    {
        $request->validate([
            'channel_name'  => 'required|string',
            'socket_id'     => 'required|string',
        ]);

        $secret = config('broadcasting.connections.pusher.secret');

        $toSign = implode('.', [
            $request->input('socket_id'),
            $request->input('channel_name')
        ]);

        $signature = hash_hmac("sha256", $toSign, $secret);
        return response()->json([
            'auth' => $signature
        ]);
    }
}
