<?php

namespace JildertMiedema\LaravelTactician;

trait DispatchesCommands
{
    /**
     * Dispatch a command to its appropriate handler.
     *
     * @param $command
     */
    protected function dispatch($command)
    {
        app('tactician.dispatcher')->dispatch($command);
    }
}
