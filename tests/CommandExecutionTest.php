<?php

use JildertMiedema\LaravelTactician\TacticianServiceProvider;
use PHPUnit\Framework\TestCase;

class CommandExecutionTest extends TestCase
{
    public function testExecution()
    {
        require_once __DIR__.'/stubs/TestCommand.php';
        require_once __DIR__.'/stubs/TestCommandHandler.php';

        $application = new \Illuminate\Foundation\Application();

        $application->bind('config', function () {
            $repository = new \Illuminate\Config\Repository();
            $repository->set("tactician", ["commandNamespace" => "", "handlerNamespace" => ""]);
            return $repository;
        });

        $handler = new TestCommandHandler();

        $application->singleton(TestCommandHandler::class, function () use ($handler) {
            return $handler;
        });

        $application->register(new TacticianServiceProvider($application));

        $command = new TestCommand('data');
        /**
         * @var \JildertMiedema\LaravelTactician\Dispatcher
         */
        $application['tactician.dispatcher']->dispatch($command);

        $this->assertCount(1, $handler->getHandledCommands());
        $this->assertEquals($command, $handler->getHandledCommands()[0]);
    }

    public function testWithMiddleware()
    {
        require_once __DIR__.'/stubs/TestCommand.php';
        require_once __DIR__.'/stubs/TestCommandHandler.php';
        require_once __DIR__.'/stubs/TestMiddleware.php';

        $application = new \Illuminate\Foundation\Application();

        $application->bind('config', function () {
            $repository = new \Illuminate\Config\Repository();
            $repository->set("tactician", ["commandNamespace" => "", "handlerNamespace" => ""]);
            return $repository;
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
