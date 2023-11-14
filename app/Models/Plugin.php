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
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
 * @property string      $download_link
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

    public function servers(): BelongsToMany
    {
        return $this->belongsToMany(Server::class);
    }

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

    protected function downloadLink(): Attribute
    {
        return Attribute::make(function ($value, array $attributes) {
            switch ($this->getPluginType()) {
                case 'modrinth':
                    return Modrinth::downloadLink($this);
                    break;
                case 'spigot':
                    return Spigot::downloadLink($this);
                    break;
                case 'unknown':
                default:
                    return '';
            }
        });
    }

    public function getPluginType(): string
    {
        // check for modrinth ID
        if ($this->modrinth_id !== null) {
            return 'modrinth';
        }

        // check for Spigot resource path
        $match = [];
        preg_match("/https:\/\/www\.(.*?)\./", $this->source, $match);
        if (count($match) > 1 && $match[1] == 'spigotmc') {
            return 'spigot';
        }

        return 'unknown';
    }

    public function updateLatestVersion(): void
    {
        switch ($this->getPluginType()) {
            case 'modrinth':
                $versions = Modrinth::versions($this->modrinth_id);
                $latest = $versions->first();
                $version = $latest['version_number'];
                break;
            case 'spigot':
                $latest = Spigot::latestVersion($this->resourceId);
                $version = $latest->get('name');
            case 'unknown':
            default:
                $version = $this->current;
        }

        $this->latest = $version;
        $this->save();
        $this->refresh();
    }
}
