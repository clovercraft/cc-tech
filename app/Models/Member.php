<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Orchid\Screen\AsSource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Orchid\Filters\Filterable;
use Orchid\Platform\Concerns\Sortable;

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
    use HasFactory, AsSource, SoftDeletes, Filterable, Sortable;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_WHITELISTED = 'whitelisted';
    public const STATUS_INACTIVE = 'inactive';

    protected $fillable = [
        'discord_id',
        'name',
        'avatar',
        'status',
        'lastseen_at'
    ];

    protected $casts = [
        'lastseen_at'       => 'date:Carbon',
        'intro_verified_at' => 'date:Carbon',
    ];

    protected function birthday(): Attribute
    {
        return Attribute::make(
            fn (?string $value) => $value == null ? null : Carbon::parse($value)->format('m/d/Y'),
            fn ($value) => $value instanceof Carbon ? $value->toDateString() : Carbon::parse($value)->toDateString()
        );
    }

    protected function playerTag(): Attribute
    {
        return Attribute::make(
            function ($value, $attributes) {
                $account = MinecraftAccount::where('member_id', $attributes['id'])->first();
                return empty($account) ? '' : $account->name;
            }
        );
    }

    protected function displayPronouns(): Attribute
    {
        return Attribute::make(
            function ($value, $attributes) {
                $options = collect([
                    'sheher'    => 'She/Her',
                    'shethey'   => 'She/They',
                    'theythem'  => 'They/Them',
                    'hehim'     => 'He/Him',
                    'hethey'    => 'He/They',
                    'itits'     => 'It/Its',
                    'any'       => 'Any Pronouns',
                ]);

                if (empty($attributes['pronouns'])) {
                    return '';
                }

                return $options->get($attributes['pronouns'], $attributes['pronouns']);
            }
        );
    }

    public function getSortColumnName(): string
    {
        return 'id';
    }

    public function minecraftAccounts(): HasMany
    {
        return $this->hasMany(MinecraftAccount::class);
    }

    public static function syncFromDiscord(array $user, Carbon $runtime): bool|Member
    {
        $userId = key_exists('id', $user) ? $user['id'] : '';

        // get the users name, with preference for global name if available
        $globalName = Arr::get($user, 'global_name');
        $username = Arr::get($user, 'username');
        $userName = empty($globalName) ? $username : $globalName;

        $userAvatar = key_exists('avatar', $user) ? $user['avatar'] : '';

        if (empty($userId) || empty($userName)) {
            Log::warning("Could not create Member record for Discord object.", [
                'username'      => $username,
                'userName'      => $userName,
                'userId'        => $userId,
                'discordObj'    => $user,
            ]);
            return false;
        }

        return Member::updateOrCreate(
            [
                'discord_id'    => $user['id']
            ],
            [
                'name'          => $userName,
                'avatar'        => 'https://cdn.discordapp.com/avatars/' . $userId . '/' . $userAvatar . '.png',
                'status'        => 'active',
                'lastseen_at'   => $runtime
            ]
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
