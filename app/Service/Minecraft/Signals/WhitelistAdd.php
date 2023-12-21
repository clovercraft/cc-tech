<?php

namespace App\Service\Minecraft\Signals;

class WhitelistAdd extends AbstractSignal
{
    protected string $name = 'whitelist add';

    public static function make(string $playerName)
    {
        return new WhitelistAdd($playerName);
    }

    public function __construct(string $playerName)
    {
        $this->args = [$playerName];
    }
}
