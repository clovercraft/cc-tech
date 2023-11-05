<?php

namespace App\Facades;

use App\Service\ModrinthService;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Collection;

/**
 * Class Modrinth.
 *
 * This class provides a static interface for interacting with the Spigot API.
 *
 * @method static Collection    versions(string $modrinthId)        Gets the list of versions for a given plugin
 */
class Modrinth extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ModrinthService::class;
    }
}
