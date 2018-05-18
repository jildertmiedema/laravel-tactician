<?php

class TestCommandHandler
{
    private static $handledCommands = [];

    public function handle(TestCommand $command)
    {
        self::$handledCommands[] = $command;
    }

    /**
     * @return array
     */
    public function getHandledCommands(): array
    {
        return self::$handledCommands;
    }

}
