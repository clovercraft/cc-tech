<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Screen\AsSource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Orchid\Filters\Filterable;

/**
 * Member model
 *
 * @property int            $id
 * @property string         $name
 * @property string|null    $discord_id
 * @property Carbon|null    $birthday
 * @property string|null    $pronouns
 * @property string|null    $bio
 * @property string|null    $avatar
 * @property string|null    $source
 * @property string         $status
 * @property Carbon|null    $lastseen_at
 * @property bool           $admin_added
 * @property Carbon         $deleted_at
 * @property Carbon         $updated_at
 * @property Carbon         $created_at
 */
class Member extends Model
{
    use HasFactory, AsSource, SoftDeletes, Filterable;

    protected $fillable = [
        'discord_id',
        'name',
        'avatar',
        'status',
        'lastseen_at'
    ];

    protected $casts = [
        'birthday' => 'date:Carbon',
        'lastseen_at'   => 'date:Carbon'
    ];

    public static function syncFromDiscord(array $user, Carbon $runtime): Member
    {
        $userId = key_exists('id', $user) ? $user['id'] : '';
        $userName = key_exists('global_name', $user) ? $user['global_name'] : '';
        $userAvatar = key_exists('avatar', $user) ? $user['avatar'] : '';

        if (empty($userId) || empty($userName) || empty($userAvatar)) {
            Log::warning("Could not create Member record for Discord object.", ['discordObj' => $user]);
            return false;
        }
        return Member::updateOrCreate(
            [
                'discord_id'    => $user['id']
            ],
            [
                'name'          => $user['global_name'],
                'avatar'        => 'https://cdn.discordapp.com/avatars/' . $user['id'] . '/' . $user['avatar'] . '.png',
                'status'        => 'active',
                'lastseen_at'   => $runtime
            ]
        );
    }
}
