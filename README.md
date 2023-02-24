# Laravel Log Driver for NewRelic Logging

This Package adds a Log Driver that formats the logs so the new relic infrastructure agent can parse them properly.

## Installation

You can install the package via composer.

For Laravel  10:
```bash
composer require teraone/laravel-newrelic-logger:"10.*"
```

For Laravel  9:
```bash
composer require teraone/laravel-newrelic-logger:"9.*"
```

## Usage

Just add a Log Channel with the driver "newrelic" to the logging.php config file:
```php
'newrelic' => [
    'driver' => 'newrelic'
]
```

There you can also add configuration like the path and the source parameter:
```php
'newrelic' => [
    'driver' => 'newrelic',
    'path' => storage_path('logs/my-log-file.log'),
    'source' => 'MyApp'
]
```

Then you can set the log channel in the .env file:
```dotenv
LOG_CHANNEL=newrelic
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.