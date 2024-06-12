<?php

namespace App\Service;

use App\Models\Member;
use App\Service\Discord\BotRequest;
use Illuminate\Support\Collection;
use Laravel\Socialite\Facades\Socialite;

class DiscordService
{
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

    public function get_members(): Collection|bool
    {
        $guild_id = env('DISCORD_GUILD_ID');
        $path = sprintf("/guilds/%s/members", $guild_id);
        $members = collect();

        $count = 50;
        $limit = 50;
        $args = ['limit' => 100];

        while ($count >= $limit) {
            $response = BotRequest::make($path)->get($args);
            if ($response == false) {
                return false;
            }
            $entries = $response->collect();
            $members = $members->merge($entries);
            $count = $entries->count();
            if ($count >= $limit) {
                $users = $entries->filter(function ($entry) {
                    return key_exists('user', $entry);
                });
                $last = $users->last();
                $args['after'] = $last['user']['id'];
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
            $response = BotRequest::make($path)->get($args);
            if ($response == false) {
                return false;
            }
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

        $response = BotRequest::make($path)->post($args);
        if ($response == false) {
            return collect();
        }
        return $response->collect();
    }

    public function ban_member(Member $member): bool
    {
        // remove member from guild

        // create ban

        return true;
    }
}
