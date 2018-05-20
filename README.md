# expressive-optimus-middleware
PSR-15 compliant middleware using jenssegers/optimus

[![Build Status](https://api.travis-ci.org/icanhazstring/optimus-middleware.svg?branch=master)](https://travis-ci.org/icanhazstring/optimus-middleware)

## Install

You can install the *expressive-optimus-middleware* library with composer:
```bash
$ composer require icanhazstring/expressive-optimus-middleware
```

## Configuration

### General dependencies

For the middleware to work, your `Container` needs a dependency to `Optimus`.
You need to provide an instance with the configuration you need.

How to configure see: https://github.com/jenssegers/optimus

### Using expressive

Include the `OptimusConfigProvider` inside your `config/config.php`:

```php
$aggregator = new ConfigAggregator([
    ...
    \icanhazstring\Middleware\OptimusConfigProvider::class,
    ...
]);
```

Make sure the `OptimusConfigProvider` is included before your autoload files!

#### Change decoded attributes

If you want to change the attributes the middleware should decode, simply provide the
`OptimusMiddleware::CONFIG_KEY` inside your autoload configuration.

```php
return [
    \icanhazstring\Middleware\OptimusMiddleware::CONFIG_KEY => ['id']
];
```
