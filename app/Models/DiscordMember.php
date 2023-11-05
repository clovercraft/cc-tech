<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Screen\AsSource;

/**
 * DiscordMember Model
 *
 * @property string         $username
 * @property string|null    $token
 * @property string|null    $refresh_token
 * @property Collection     $minecraftAccounts
 * @property Carbon         $last_seen
 * @property Carbon         $created_at
 * @property Carbon         $updated_at
 * @property Carbon|null    $deleted_at
 */
class DiscordMember extends Model
{
    use HasFactory, SoftDeletes, AsSource;

    public function minecraftAccounts(): HasMany
    {
        return $this->hasMany(MinecraftAccount::class);
    }
}
