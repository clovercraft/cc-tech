<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Screen\AsSource;

/**
 * MinecraftAccount Model
 *
 * @property string         $name
 * @property string|null    $uuid
 * @property string         $status
 * @property DiscordMember  $discordMember
 * @property Carbon         $created_at
 * @property Carbon         $updated_at
 * @property Carbon|null    $deleted_at
 */
class MinecraftAccount extends Model
{
    use HasFactory, SoftDeletes, AsSource;

    // status constants
    public const ACTIVE     = 'active';
    public const INACTIVE   = 'inactive';
    public const BANNED     = 'banned';

    public function discordMember(): BelongsTo
    {
        return $this->belongsTo(DiscordMember::class);
    }
}
