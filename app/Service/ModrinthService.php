<?php

namespace App\Service;

use App\Models\Plugin;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

/**
 * Modrinth Service
 *
 * Implements the Labrinth API to retrieve plugin data
 * API Specification: https://docs.modrinth.com/api-spec
 */
class ModrinthService
{

    public const USER_AGENT = "ClovercraftTechManager";
    public const API_BASE = 'https://api.modrinth.com/v2/';

    public function versions(string $modrinthId): Collection
    {
        $pattern = "project/{id}/version?featured=true";
        return $this->callApi($pattern, ['id' => $modrinthId]);
    }

    public function downloadLink(Plugin $plugin): string
    {
        $versions = $this->versions($plugin->modrinth_id);
        $latest = $versions->first();
        $url = $latest['files'][0]['url'];
        return $url;
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
