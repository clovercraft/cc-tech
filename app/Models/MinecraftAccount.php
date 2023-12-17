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
 * @property Member         $member
 * @property Carbon|null    $whitelisted_at
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

    protected $casts = [
        'whitelsted_at' => 'date:Carbon'
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
