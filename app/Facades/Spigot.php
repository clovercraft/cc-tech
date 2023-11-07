<?php

namespace App\Facades;

use App\Service\SpigotService;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Collection;

/**
 * Class Spigot.
 *
 * This class provides a static interface for interacting with the Spigot API.
 *
 * @method static Collection    pluginInfo(string $resourceId)      Gets the basic details about a given plugin by resource ID
 * @method static Collection    latestVersion(string $resourceId)   Get the latest version information for a given plugin
 */
class Spigot extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SpigotService::class;
    }
}
