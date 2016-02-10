<?php

class TestMiddleware implements \League\Tactician\Middleware
{

    public $handledCommands = [];

    /**
     * @param object $command
     * @param callable $next
     *
     * @return mixed
     */
    public function execute($command, callable $next)
    {
        $this->handledCommands[] = $command;

        return $next($command);
    }
}