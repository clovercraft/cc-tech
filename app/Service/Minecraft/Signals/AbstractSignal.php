<?php

namespace App\Service\Minecraft\Signals;

use App\Events\SmpSignal;

abstract class AbstractSignal
{
    protected string $name;
    protected string $token;

    public array $args;

    public function withToken(string $token)
    {
        $this->token = $token;
        return $this;
    }

    public function send()
    {
        if (empty($this->token) || empty($this->name)) {
            throw new \Exception("SMP Signal missing required parameters");
        }

        if (empty($this->args)) {
            $this->args = [];
        }

        SmpSignal::dispatch($this->name, $this->args, $this->token);
    }
}
