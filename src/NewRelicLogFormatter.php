<?php

namespace teraone\NewRelicLogger;

use Monolog\Formatter\JsonFormatter;

class NewRelicLogFormatter extends JsonFormatter
{

    public function __construct(protected string $source, protected array $additional_info, int $batchMode = self::BATCH_MODE_JSON, bool $appendNewline = true, bool $ignoreEmptyContextAndExtra = false, bool $includeStacktraces = false)
    {
        parent::__construct($batchMode, $appendNewline, $ignoreEmptyContextAndExtra, $includeStacktraces);
    }

    public function format(array $record): string
    {
        $record = $this->includeMetaData($record);

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
        if (app()->runningInConsole()) {
            $record['console'] = true;
        } else {
            $record['full_url'] = request()?->fullUrl();
        }

        foreach ($this->additional_info as $key => $value) {
            $record[$key] = $value;
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