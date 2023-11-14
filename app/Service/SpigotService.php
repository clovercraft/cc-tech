<?php

namespace App\Service;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use App\Models\Plugin;

/**
 * Spigot Service
 *
 * Implements the API documented on Siget.org to retrieve plugin data
 * API Specification: https://spiget.org/documentation
 */
class SpigotService
{

    public const USER_AGENT = "ClovercraftTechManager";
    public const API_BASE = "https://api.spiget.org/v2/";

    public function __construct()
    {
    }

    public function pluginInfo(string $resourceId): Collection
    {
        $pattern = 'resources/%s';
        return $this->callApi($pattern, [$resourceId]);
    }

    public function latestVersion(string $resourceId)
    {
        $pattern = 'resources/%s/versions/latest';
        return $this->callApi($pattern, [$resourceId]);
    }

    public function downloadLink(Plugin $plugin): string
    {
        $resourceId = $plugin->resource_id;
        $latest = $this->latestVersion($resourceId);
        $versionId = $latest->get('id');

        $pattern = self::API_BASE . 'resources/%s/versions/%s/download';
        $uri = sprintf($pattern, $resourceId, $versionId);
        return $uri;
    }

    private function callApi(string $endpoint, array $params): Collection
    {
        $pattern = self::API_BASE . $endpoint;
        $uri = sprintf($pattern, ...$params);
        $response = Http::withHeader('User-Agent', self::USER_AGENT)
            ->get($uri);

        if ($response->failed()) {
            $response->throw();
        }

        return $response->collect();
    }
}
