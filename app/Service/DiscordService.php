<?php

namespace App\Service;

use App\Models\AppSetting;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

class DiscordService
{

    private const API_URL = 'https://discord.com/api/';
    private const API_VERSION = 10;

    private string $authToken;
    private string $refreshToken;

    public function __construct()
    {
        $this->getAuthToken(true);
        $this->getRefreshToken(true);
    }

    public function authorize()
    {
        // https://discord.com/api/oauth2/authorize?client_id=1160334357899264021&permissions=8&response_type=code&redirect_uri=https%3A%2F%2Flocalhost%2Foauth%2Freturn&scope=bot+guilds.members.read+guilds
        return Socialite::driver('discord')
            ->scopes(['bot', 'guilds.members.read', 'guilds'])
            ->with([
                'permissions' => 8,
                'guild_id'    => env('DISCORD_GUILD_ID')
            ])
            ->redirect();
    }

    public function botInviteLink(): string
    {
        return sprintf("https://discord.com/oauth2/authorize?client_id=%s&scope=bot&permissions=8", env('DISCORD_CLIENT_ID'));
    }

    public function refresh_authorization()
    {
        $token = AppSetting::access('discord_token');
        $refresh_token = AppSetting::access('discord_refresh_token');

        $data = [
            'grant_type'    => 'refresh_token',
            'refresh_token' => AppSetting::where('slug', 'discord_refresh_token')->first()->value,
        ];

        $uri = $this->request_url('/oauth2/token');
    }

    public function checkAppStatus(): Collection
    {
        $response = $this->get('/oauth2/@me');
        return $response->collect();
    }

    public function getGuildMembers(): Collection
    {
        $guild_id = env('DISCORD_GUILD_ID');
        $path = sprintf("/guilds/%s/members", $guild_id);
        $members = collect();

        $count = 50;
        $limit = 50;

        while ($count >= $limit) {
            $response = $this->bot_get($path, [
                'limit' => 100
            ]);
            $entries = $response->collect();
            $members->merge($entries);
            $count = $entries->count();
        }

        return $members;
    }

    public function getAuthToken(bool $refresh = false): string
    {
        if ($refresh || !isset($this->authToken)) {
            $this->authToken = AppSetting::access('discord_token');
        }
        return $this->authToken;
    }

    public function getRefreshToken(bool $refresh = false): string
    {
        if ($refresh || !isset($this->refreshToken)) {
            $this->refreshToken = AppSetting::access('discord_refresh_token');
        }
        return $this->refreshToken;
    }

    private function get(string $path, array $query_string = []): Response
    {
        $uri = $this->request_url($path);
        return Http::withToken($this->getAuthToken())
            ->withQueryParameters($query_string)
            ->get($uri);
    }

    private function bot_get(string $path, array $query_string = []): Response
    {
        $uri = $this->request_url($path);
        $token = env('DISCORD_BOT_TOKEN');
        return Http::withToken($token, 'Bot')
            ->withQueryParameters($query_string)
            ->get($uri);
    }

    private function request_url(string $path = ''): string
    {
        $uri = self::API_URL;
        // if (!str_contains($path, 'oauth2')) {
        //     $uri .= self::API_VERSION;
        // }
        $uri .= $path;
        return $uri;
    }
}
