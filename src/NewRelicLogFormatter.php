<?php

namespace teraone\NewRelicLogger;

use Monolog\Formatter\JsonFormatter;
use Monolog\LogRecord;

class NewRelicLogFormatter extends JsonFormatter
{
    public function format(LogRecord $record): string
    {
        $record = $this->includeMetaData($record->toArray());

        $normalized = $this->normalize($record);

        if (isset($normalized['context']) && $normalized['context'] === []) {
            if ($this->ignoreEmptyContextAndExtra) {
                unset($normalized['context']);
            } else {
                $normalized['context'] = new \stdClass;
            }
        }
        if (isset($normalized['extra']) && $normalized['extra'] === []) {
            if ($this->ignoreEmptyContextAndExtra) {
                unset($normalized['extra']);
            } else {
                $normalized['extra'] = new \stdClass;
            }
        }

        return $this->toJson($normalized, true).($this->appendNewline ? "\n" : '');
    }

    public function includeMetaData(array $record): array
    {
        $record['hostname'] = gethostname();

        $record['source'] = config('newrelic.source');

        $record['env'] = config('app.env');

        if (app()->runningInConsole()) {
            $record['console'] = true;
        } else {
            $record['full_url'] = request()?->fullUrl();
        }

        if (isset($record['context']['user_id'])) {
            $record['user_id'] = $record['context']['user_id'];
        }

        if (function_exists('newrelic_get_linking_metadata')) {
            $linking_data = newrelic_get_linking_metadata();
            $record['newrelic-context'] = $linking_data;

            $record['timestamp'] = intval(
                $record['datetime']->format('U.u') * 1000
            );
        }

        return $record;
    }

}