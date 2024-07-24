<?php

namespace App\Service\Minecraft\Signals;

class WhitelistRemove extends AbstractSignal
{
    protected string $name = 'whitelist remove';

    public static function make(string $playerName)
    {
        return new WhitelistRemove($playerName);
    }

    public function __construct(string $playerName)
    {
        $this->args = [$playerName];
    }
}
