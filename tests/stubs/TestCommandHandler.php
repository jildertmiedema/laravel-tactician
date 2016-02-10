<?php

class TestCommandHandler
{
    public $handledCommands = [];

    public function handle(TestCommand $command)
    {
        $this->handledCommands[] = $command;
    }
}
