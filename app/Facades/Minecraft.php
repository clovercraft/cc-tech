<?php

namespace App\Facades;

use App\Service\MinecraftService;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Collection;

/**
 * Class Minecraft.
 *
 * This class provides a static interface for interacting with the Minecraft Player DB API.
 *
 */
class Minecraft extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return MinecraftService::class;
    }
}
