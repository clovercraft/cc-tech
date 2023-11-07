<?php

namespace App\Models;

use App\Facades\Modrinth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Orchid\Screen\AsSource;
use App\Facades\Spigot;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Plugin model class
 *
 * @property int         $id
 * @property string      $name
 * @property string      $description
 * @property string      $source
 * @property string      $latest
 * @property string      $modrinth_id
 * @property bool        $in_use
 * @property string      $resource_id
 * @property Carbon      $created_at
 * @property Carbon      $updated_at
 * @property Carbon|null $deleted_at
 */
class Plugin extends Model
{
    use HasFactory, SoftDeletes, AsSource;

    protected $fillable = [
        'name',
        'description',
        'source',
        'current',
        'latest',
        'modrinth_id',
        'in_use'
    ];

    protected function details(): Attribute
    {
        return Attribute::make(function ($value, array $attributes) {
            $match = [];
            preg_match("/https:\/\/www\.spigotmc\.org.*?\.(.*?)\//", $attributes['source'], $match);
            $id = $match[1];
            return Spigot::pluginInfo($id);
        });
    }

    protected function resourceId(): Attribute
    {
        return Attribute::make(function ($value, array $attributes) {
            $match = [];
            preg_match("/https:\/\/www\.spigotmc\.org.*?\.(.*?)\//", $attributes['source'], $match);
            $id = $match[1];
            return $id;
        });
    }

    public function isSpigotPlugin(): bool
    {
        $match = [];
        preg_match("/https:\/\/www\.(.*?)\./", $this->source, $match);
        if (count($match) > 1 && $match[1] = 'spigotmc') {
            return true;
        }
        return false;
    }

    public function updateLatestVersion(): void
    {
        if ($this->modrinth_id !== null) {
            $versions = Modrinth::versions($this->modrinth_id);
            $latest = $versions->first();
            $version = $latest['version_number'];
        } else if ($this->isSpigotPlugin()) {
            $latest = Spigot::latestVersion($this->resourceId);
            $version = $latest->get('name');
        }

        $this->latest = $version;
        $this->save();
        $this->refresh();
    }
}