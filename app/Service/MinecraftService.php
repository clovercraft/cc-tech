<?php

namespace App\Service;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class MinecraftService
{
    public function getAccount(string $id): array
    {
        $uri = sprintf("https://playerdb.co/api/player/minecraft/%s", $id);
        $response = Http::get($uri);
        if ($response->failed()) {
            throw new Exception("PlayerDB API call failed.");
        }
        $data = $response->collect();
        if ($data->get('code') !== 'player.found') {
            throw new Exception("Failed to resolve player by given ID: " . $id);
        }
        return $data->get('data')['player'];
    }
}
