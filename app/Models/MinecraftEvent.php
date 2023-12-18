<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Screen\AsSource;

/**
 * MinecraftEvent Model Class
 *
 * @property int                $id
 * @property string             $event_type
 * @property array              $context
 * @property MinecraftAccount   $minecraftAccount
 * @property Carbon             $created_at
 * @property Carbon             $updated_at
 * @property Carbon|null        $deleted_at
 */
class MinecraftEvent extends Model
{
    use HasFactory, AsSource, SoftDeletes;

    protected function context(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => json_decode($value),
            set: fn (array $value) => json_encode($value)
        );
    }

    public function MinecraftAccount(): BelongsTo
    {
        return $this->belongsTo(MinecraftAccount::class);
    }
}
