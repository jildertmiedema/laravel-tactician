<?php

namespace JildertMiedema\LaravelTactician;

use League\Tactician\Exception\MissingHandlerException;
use League\Tactician\Handler\Locator\HandlerLocator;
use Illuminate\Contracts\Container\Container;

class Locator implements HandlerLocator
{
    /**
     * @var Container
     */
    private $container;
    /**
     * @var
     */
    private $commandNamespace;
    /**
     * @var
     */
    private $handlerNamespace;

    public function __construct(Container $container, $commandNamespace, $handlerNamespace)
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

        $handlerName = $this->handlerNamespace.'\\'.trim($command, '\\').'Handler';

        if (!class_exists($handlerName)) {
            throw MissingHandlerException::forCommand($commandName);
        }

        $handler = $this->container->make($handlerName);

        return $handler;
    }
}
