<?php

namespace teraone\NewRelicLogger;

use Closure;
use Illuminate\Support\ServiceProvider;

class NewRelicLoggerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'newrelic');
    }

    public function boot()
    {
        $this->app->make('config')->set('logging.channels.newrelic', [
            'driver' => config('newrelic.driver'),
            'path' => storage_path('logs/'.config('newrelic.log_file')),
            'level' => config('newrelic.log_level'),
            'formatter' => NewRelicLogFormatter::class
        ]);

        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('newrelic.php')
        ]);
    }
}