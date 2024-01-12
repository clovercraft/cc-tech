<?php

namespace App\Service;

use App\Models\AppSetting;
use App\Models\Member;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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

    public function has_authorization(): bool
    {
        // check token is set
        $token = $this->getAuthToken(true);
        if (empty($token)) {
            return false;
        }

        // check status
        $authentication = $this->checkAppStatus();
        return $authentication->has('user');
        if ($authentication->has('user') && $authentication->has('expires')) {
            $expires = Carbon::parse($authentication->get('expires'));
            $diff = now()->diffInMinutes($expires, false);
            return $diff > 0;
        }

        return false;
    }

    public function botInviteLink(): string
    {
        return sprintf("https://discord.com/oauth2/authorize?client_id=%s&scope=bot&permissions=8", env('DISCORD_CLIENT_ID'));
    }

    /**
     * I never got ths to work, going to just check auth status instead.
     * @deprecated 0.0
     *
     * @return void
     */
    public function refresh_authorization()
    {
        $refresh_token = $this->getRefreshToken();
        $client_id = env('DISCORD_CLIENT_ID');
        $client_secret = env('DISCORD_CLIENT_SECRET');
        $auth = "Basic " . $client_id . ":" . $client_secret;

        $data = [
            'grant_type'    => 'refresh_token',
            'refresh_token' => $refresh_token,
        ];

        $uri = 'https://discord.com/api/v10/oauth2/token';
        Log::info("Attempting Discord token refresh", [$uri, $auth, $data]);
        // $response = Http::withHeaders([
        //     "Authorization" => $auth,
        //     "Content-Type"  => "application/x-www-form-urlencoded"
        // ])
        //     ->post($uri, $data);
        $response = Http::withBasicAuth($client_id, $client_secret)
            ->contentType('application/x-www-form-urlencoded')
            ->post($uri, $data);
        if ($response->failed()) {
            dd($response->body());
        }
        // $user = Socialite::driver('discord')->userFromToken($this->getAuthToken());

        return $response;
    }

    public function checkAppStatus(): Collection
    {
        $response = $this->get('/oauth2/@me');
        return $response->collect();
    }

    public function get_members(): Collection
    {
        $guild_id = env('DISCORD_GUILD_ID');
        $path = sprintf("/guilds/%s/members", $guild_id);
        $members = collect();

        $count = 50;
        $limit = 50;
        $after = '';
        $args = ['limit' => 100];

        while ($count >= $limit) {
            $response = $this->bot_get($path, $args);
            $entries = $response->collect();
            $members = $members->merge($entries);
            $count = $entries->count();
            if ($count >= $limit) {
                $args['after'] = $this->parseLastUser($entries);
            }
        }

        return $members;
    }

    public function verify_intro_message(Member $member, ?int $limit = 5): bool
    {
        $channel_id = env('DISCORD_INTRO_CHANNEL_ID');
        $path = sprintf("/channels/%s/messages", $channel_id);

        $perPage = 100;
        $page = 0;
        $verified = false;
        $args = ['limit' => $perPage];

        while ($verified == false && $page < $limit) {
            $page++;
            $response = $this->bot_get($path, $args);
            $entries = $response->collect();
            $matches = $entries->where('author.id', $member->discord_id);
            if ($matches->count() >= 1) {
                $verified = true;
            }
        }

        return $verified;
    }

    public function send_discord_message(string $content)
    {
        $channel_id = env('DISCORD_BROADCAST_CHANNEL_ID');
        $path = sprintf("/channels/%s/messages", $channel_id);

        $args = [
            'content' => $content,
        ];

        $response = $this->bot_post($path, $args);
        return $response->collect();
    }

    private function parseLastUser(Collection $entries): string
    {
        $users = $entries->filter(function ($entry) {
            return key_exists('user', $entry);
        });
        $last = $users->last();
        return $last['user']['id'];
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

    private function bot_post(string $path, array $args = []): Response
    {
        $uri = $this->request_url($path);
        $token = env('DISCORD_BOT_TOKEN');
        return Http::withToken($token, 'Bot')
            ->post($uri, $args);
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
