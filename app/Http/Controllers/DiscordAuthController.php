<?php

namespace App\Http\Controllers;

use App\Facades\Discord;
use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class DiscordAuthController extends Controller
{

    public function discord_authenticate()
    {
        if (!Auth::user()->hasAccess('platform.systems.settings')) {
            return redirect()->route('platform.home');
        }
        return Discord::authorize();
    }

    public function discord_authorize(Request $request)
    {
        $user = Socialite::driver('discord')->user();
        $this->createSettingRecords($user->token, $user->refreshToken, $user->expiresIn);
        return redirect()->route('platform.systems.settings');
    }

    private function createSettingRecords(string $token, string $refresh, string $expires_in)
    {
        $settings = [
            [
                'slug'  => 'discord_token',
                'label' => 'Discord Token',
                'type'  => 'string',
                'value' => $token
            ],
            [
                'slug'  => 'discord_refresh_token',
                'label' => 'Discord Refresh Token',
                'type'  => 'string',
                'value' => $refresh
            ],
            [
                'slug'  => 'discord_token_expires_in',
                'label' => 'Discord Token Expiration',
                'type'  => 'string',
                'value' => $expires_in
            ],
        ];

        foreach ($settings as $setting) {
            AppSetting::updateOrCreate(
                [
                    'slug' => $setting['slug']
                ],
                [
                    'label' => $setting['label'],
                    'type'  => $setting['type'],
                    'value' => $setting['value']
                ]
            );
        }
    }
}
