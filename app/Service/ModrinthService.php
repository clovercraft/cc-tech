<?php

namespace App\Service;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class ModrinthService
{

    public const USER_AGENT = "ClovercraftTechManager";
    public const API_BASE = 'https://api.modrinth.com/v2/';

    public function versions(string $modrinthId): Collection
    {
        $pattern = "project/{id}/version?featured=true";
        return $this->callApi($pattern, ['id' => $modrinthId]);
    }

    private function callApi(string $endpoint, array $params): Collection
    {
        $pattern = self::API_BASE . $endpoint;

        $response = Http::withHeader('User-Agent', self::USER_AGENT)
            ->withUrlParameters($params)
            ->get($pattern);

        if ($response->failed()) {
            $response->throw();
        }

        return $response->collect();
    }
}
