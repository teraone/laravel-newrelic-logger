<?php

namespace teraone\NewRelicLogger;

use Illuminate\Contracts\Container\Container;
use Illuminate\Log\LogManager;
use Illuminate\Support\ServiceProvider;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

class NewRelicLoggerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app['log'] instanceof LogManager) {
            $this->app['log']->extend('newrelic', function (Container $app, array $config) {

                //Creates a Log Handler with the passed in config
                $handler = new StreamHandler(
                    $config['path'] ?? storage_path('logs/'.'newrelic.log'), $config['level'] ?? Level::Debug,
                    $config['bubble'] ?? true, $config['permission'] ?? null, $config['locking'] ?? false
                );

                //Initializes the custom Formatter and adds it to the Handler
                $formatter = new NewRelicLogFormatter($config['source'] ?? env('APP_NAME'));
                $handler->setFormatter($formatter);

                //Creates and returns the custom Logger with the Handler
                return new Logger('newrelic', [$handler]);
            });
        }
    }
}