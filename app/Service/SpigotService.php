<?php

namespace App\Service;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class SpigotService
{

    public const USER_AGENT = "ClovercraftTechManager";
    public const API_BASE = "https://api.spiget.org/v2/";

    public function __construct()
    {
    }

    public function pluginInfo(string $resourceId): Collection
    {
        $pattern = 'resources/{resource}';
        return $this->callApi($pattern, ['resource' => $resourceId]);
    }

    public function latestVersion(string $resourceId)
    {
        $pattern = 'resources/{resource}/versions/latest';
        return $this->callApi($pattern, ['resource' => $resourceId]);
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
