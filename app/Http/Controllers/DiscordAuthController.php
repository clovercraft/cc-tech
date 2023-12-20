<?php

namespace App\Http\Controllers;

use App\Facades\Discord;
use App\Models\AppSetting;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Orchid\Platform\Models\Role;

class DiscordAuthController extends Controller
{

    public function discord_member_auth()
    {
        Session::put('oauth_as_member', true);
        return Socialite::driver('discord')
            ->redirect();
    }

    public function discord_member_authorize($user, $request)
    {
        Log::info("Discord auth: member");

        // sync the member record
        $member = Member::syncFromDiscord($user->user, now());

        // if a user record doesn't exist, create one
        if (empty($member->user)) {
            // check that we don't already have a user for that email address
            if (User::where('email', $user->email)->count() > 0) {
                $record = User::where('email', $user->email)->first();
            } else {
                $record = new User();
                $record->name = $user->name;
                $record->email = $user->email;
                $record->password = fake()->password(12);
                $record->save();

                // grant basic perms
                $role = Role::where('slug', 'member')->first();
                $record->addRole($role);
                $record->save();
                $record->refresh();
            }
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

        $as_member = Session::pull('oauth_as_member', false);

        if ($as_member) {
            return $this->discord_member_authorize($user, $request);
        }

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
