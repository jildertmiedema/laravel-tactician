# laravel-tactician
Tactician command bus for laravel 5+

[![Build Status](https://img.shields.io/travis/jildertmiedema/laravel-tactician/master.svg?style=flat-square)](https://travis-ci.org/jildertmiedema/laravel-tactician)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/jildertmiedema/laravel-tactician.svg?style=flat-square)](https://packagist.org/packages/jildertmiedema/laravel-tactician)
[![Total Downloads](https://img.shields.io/packagist/dt/jildertmiedema/laravel-tactician.svg?style=flat-square)](https://packagist.org/packages/jildertmiedema/laravel-tactician)

## Install

```
composer require jildertmiedema/laravel-tactician
```

Add ```JildertMiedema\LaravelTactician\TacticianServiceProvider``` to your app.php

Run this in the command line:
```
php artisan vendor:publish
```

Edit ```config/tactician.php``` and set your namespaces

## Usage
In your controllers or console commands you can use the trait ```JildertMiedema\LaravelTactician\DispatchesCommands```.

```php

use YourApp\Commmands\DoSomethingCommand;
use Illuminate\Routing\Controller as BaseController;
use JildertMiedema\LaravelTactician\DispatchesCommands;

class Controller extends BaseController
{
    use DispatchesCommands;

    public function store(Request $request)
    {
        $command = new DoSomethingCommand($request->get('id'), $request->get('value'));
        $this->dispatch($command);

        return redirect('/');
    }
}
```
## Extending

### Middleware

In your own ServiceProvider:
```php
$this->app['tactician.middleware']->prepend('your.middleware');

$this->app->bind('your.middleware', function () {
    return new MiddleWare
});
```

### Locator
The default locator is set in the container by ```tactician.handler.locator```, of course you can change it.

In your own ServiceProvider:
```php
$this->bind('tactician.handler.locator', function () {
    return new YourLocator();
});
```
