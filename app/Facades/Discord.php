<?php

namespace App\Facades;

use App\Service\DiscordService;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Collection;

/**
 * Class Discord.
 *
 * This class provides a static interface for interacting with the Discord API.
 *
 */
class Discord extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return DiscordService::class;
    }
}
