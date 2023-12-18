<?php

namespace App\Http\Controllers;

use App\Facades\Discord;
use App\Models\AppSetting;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class DiscordAuthController extends Controller
{

    public function discord_member_auth()
    {
        return Discord::authorizeMember();
    }

    public function discord_member_authorize(Request $request)
    {
        $user = Socialite::driver('discord')->user();
        Log::info("Discord auth: member");

        // sync the member record
        $member = Member::syncFromDiscord($user->user, now());

        // if a user record doesn't exist, create one
        if (empty($member->user)) {
            $record = new User();
            $record->name = $user->name;
            $record->email = $user->email;
            $record->password = fake()->password(12);
            $record->save();
            $member->user()->associate($record);
            $member->save();
        }
        Auth::login($member->user);
        return redirect()->route('platform.member.self');
    }

    public function discord_authenticate()
    {
        if (!Auth::user()->hasAccess('staff.system')) {
            return redirect()->route('platform.home');
        }
        return Discord::authorize();
    }

    public function discord_authorize(Request $request)
    {
        $user = Socialite::driver('discord')->user();
        Log::info("Discord auth", [$user->token, $user->refreshToken, $user->expiresIn]);
        $this->createSettingRecords($user->token, $user->refreshToken, $user->expiresIn);
        return redirect()->route('staff.system');
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
