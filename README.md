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

There you can configure what else should be included in the logs:
```php
'newrelic' => [
    'driver' => 'newrelic',
    
    'additional_info' => [
        'env' => env('APP_ENV'),
        'hostname' => gethostname()
    ]
]
```

Then you can set the log channel in the .env file:
```dotenv
LOG_CHANNEL=newrelic
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.