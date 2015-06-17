<?php

use JildertMiedema\LaravelTactician\TacticianServiceProvider;

class TestCommandExecution extends PHPUnit_Framework_TestCase
{
    public function testExecution()
    {
        require_once __DIR__.'/stubs/TestCommand.php';
        require_once __DIR__.'/stubs/TestCommandHandler.php';

        $application = new \Illuminate\Foundation\Application();

        $application->bind('config', function () {
            return new \Illuminate\Config\Repository();
        });

        $handler = new TestCommandHandler();
        TestCommandHandler::$handledCommands = [];

        $application->singleton(TestCommandHandler::class, function () use (&$handler) {
            return $handler;
        });

        $application->register(new TacticianServiceProvider($application));

        $command = new TestCommand('data');
        $application['tactician.dispatcher']->dispatch($command);

        $this->assertCount(1, TestCommandHandler::$handledCommands);
        $this->assertEquals($command, TestCommandHandler::$handledCommands[0]);
    }
}
