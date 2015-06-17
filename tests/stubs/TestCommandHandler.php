<?php

class TestCommandHandler
{
    public static $handledCommands = [];

    public function handle(TestCommand $command)
    {
        static::$handledCommands[] = $command;
    }
}
