# laravel-tactician
Tactician command bus for laravel 5+

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

## Extending

### Middleware

In your own ServiceProvider:
```php
  $this->app['tactician.middleware'][] = 'your.middleware';
  $this->bind('your.middleware', function () {
    return new MiddleWare
  });
```

### Locator
The default locator is set in the container by ```tactician.handler.locator```, of cource you can change it.

In your own ServiceProvider:
```php
  $this->bind('tactician.handler.locator', function () {
    return new YourLocator();
  });
```

