<?php

namespace JildertMiedema\LaravelTactician;

use League\Tactician\CommandBus;

class Dispatcher
{
    /**
     * @var CommandBus
     */
    private $bus;

    public function __construct(CommandBus $bus)
    {
        $this->bus = $bus;
    }

    public function dispatch($command)
    {
        $this->bus->handle($command);
    }
}
