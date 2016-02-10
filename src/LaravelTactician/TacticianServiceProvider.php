<?php

namespace JildertMiedema\LaravelTactician;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use JildertMiedema\LaravelTactician\Dispatcher as TacticianDispatcher;
use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;

class TacticianServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/tactician.php' => config_path('tactician.php'),
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/tactician.php', 'tactician'
        );

        $this->registerServices();
    }

    protected function registerServices()
    {
        $this->app->singleton('tactician.middleware', function () {
            return new Collection(['tactician.middleware.command_handler']);
        });
        $this->app->bind('tactician.handler.locator', function () {
            $config = $this->app['config'];
            $commandNamespace = $config->get('tactician.commandNamespace');
            $handlerNamespace = $config->get('tactician.handlerNamespace');

            return new Locator($this->app, $commandNamespace, $handlerNamespace);
        });
        $this->app->bind('tactician.middleware.command_handler', function () {
            return new CommandHandlerMiddleware(
                $this->app['tactician.handler.command_name_extractor.class_name'],
                $this->app['tactician.handler.locator'],
                $this->app['tactician.handler.method_name_inflector']
            );
        });
        $this->app->bind('tactician.handler.command_name_extractor.class_name', function () {
            return new ClassNameExtractor();
        });
        $this->app->bind('tactician.handler.method_name_inflector', function () {
            return new HandleInflector();
        });
        $this->app->bind('tactician.middleware.resolved', function () {
            return array_map(function ($name) {
                if (is_string($name)) {
                    return $this->app[$name];
                }

                return $name;
            }, $this->app['tactician.middleware']->all());
        });
        $this->app->bind('tactician.commandbus', function () {
            $middleware = $this->app['tactician.middleware.resolved'];

            return new CommandBus($middleware);
        });
        $this->app->bind('tactician.dispatcher', function () {
            $bus = $this->app['tactician.commandbus'];

            return new TacticianDispatcher($bus);
        });
    }
}
