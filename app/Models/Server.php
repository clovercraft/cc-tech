<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Screen\AsSource;

/**
 * Server Model
 *
 * @property int    $id
 * @property string $name
 * @property string $ip
 * @property string $type
 * @property string $current_version
 * @property string $api_key
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */
class Server extends Model
{
    use HasFactory, SoftDeletes, AsSource;

    public const VANILLA    = 'vanilla';
    public const FORGE      = 'forge';
    public const FABRIC     = 'fabric';

    protected $fillable = [
        'name',
        'ip',
        'type',
        'current_version',
        'api_key',
    ];

    public function plugins(): BelongsToMany
    {
        return $this->belongsToMany(Plugin::class);
    }
}
