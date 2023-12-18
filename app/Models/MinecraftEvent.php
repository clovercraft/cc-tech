<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

/**
 * MinecraftEvent Model Class
 *
 * @property int                $id
 * @property string             $event_type
 * @property string             $context
 * @property MinecraftAccount   $minecraftAccount
 * @property Carbon             $created_at
 * @property Carbon             $updated_at
 * @property Carbon|null        $deleted_at
 */
class MinecraftEvent extends Model
{
    use HasFactory, AsSource, SoftDeletes, Filterable;

    public function minecraftAccount(): BelongsTo
    {
        return $this->belongsTo(MinecraftAccount::class);
    }
}
