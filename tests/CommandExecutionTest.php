<?php

use JildertMiedema\LaravelTactician\TacticianServiceProvider;

class CommandExecutionTest extends PHPUnit_Framework_TestCase
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

        $application->singleton(TestCommandHandler::class, function () use ($handler) {
            return $handler;
        });

        $application->register(new TacticianServiceProvider($application));

        $command = new TestCommand('data');
        $application['tactician.dispatcher']->dispatch($command);

        $this->assertCount(1, $handler->handledCommands);
        $this->assertEquals($command, $handler->handledCommands[0]);
    }

    public function testWithMiddleware()
    {
        require_once __DIR__.'/stubs/TestCommand.php';
        require_once __DIR__.'/stubs/TestCommandHandler.php';
        require_once __DIR__.'/stubs/TestMiddleware.php';

        $application = new \Illuminate\Foundation\Application();

        $application->bind('config', function () {
            return new \Illuminate\Config\Repository();
        });

        $application->singleton(TestCommandHandler::class, function () use (&$handler) {
            return new TestCommandHandler();
        });

        $application->register(new TacticianServiceProvider($application));

        $application['tactician.middleware']->prepend('test.middleware');

        $middleware = new TestMiddleware();

        $application->bind('test.middleware', function () use ($middleware) {
            return $middleware;
        });

        $command = new TestCommand('data');
        $application['tactician.dispatcher']->dispatch($command);

        $this->assertCount(1, $middleware->handledCommands);
        $this->assertEquals($command, $middleware->handledCommands[0]);
    }
}
