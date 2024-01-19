<?php

namespace JildertMiedema\LaravelTactician;

use Illuminate\Contracts\Container\Container;
use League\Tactician\Exception\MissingHandlerException;
use League\Tactician\Handler\Locator\HandlerLocator;

class Locator implements HandlerLocator
{
    private Container $container;
    private string $commandNamespace;
    private string $handlerNamespace;

    public function __construct(Container $container, string $commandNamespace, string $handlerNamespace)
    {
        $this->container = $container;
        $this->commandNamespace = $commandNamespace;
        $this->handlerNamespace = $handlerNamespace;
    }

    /**
     * Retrieves the handler for a specified command.
     *
     * @param string $commandName
     *
     * @return object
     *
     * @throws MissingHandlerException
     */
    public function getHandlerForCommand($commandName)
    {
        $command = str_replace($this->commandNamespace, '', $commandName);

        $handlerName = $this->handlerNamespace . '\\' . trim($command, '\\') . 'Handler';

//        dd($command, $handlerName, $commandName);

        if (!class_exists($handlerName)) {
            throw MissingHandlerException::forCommand($commandName);
        }

        $handler = $this->container->make($handlerName);

        return $handler;
    }
}
