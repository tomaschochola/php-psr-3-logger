<?php

/**
 * @author Tomáš Chochola <tomaschochola@tomaschochola.cz>
 * @copyright © 2026 Tomáš Chochola <tomaschochola@tomaschochola.cz>
 *
 * @license CC-BY-ND-4.0
 *
 * @see {@link https://creativecommons.org/licenses/by-nd/4.0/} License
 * @see {@link https://github.com/tomaschochola} GitHub Profile
 * @see {@link https://github.com/sponsors/tomaschochola} GitHub Sponsors
 */

declare(strict_types=1);

namespace TomasChochola\Psr\Log;

use LogicException;
use Override;
use Psr\Clock\ClockInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Stringable;
use UnexpectedValueException;

use function array_is_list;
use function array_map;
use function assert;
use function fwrite;
use function is_array;
use function is_bool;
use function is_float;
use function is_int;
use function is_string;
use function json_encode;
use function mb_strlen;
use function mb_strtoupper;

use const JSON_INVALID_UTF8_SUBSTITUTE;
use const JSON_PARTIAL_OUTPUT_ON_ERROR;
use const JSON_PRESERVE_ZERO_FRACTION;
use const JSON_UNESCAPED_SLASHES;
use const JSON_UNESCAPED_UNICODE;
use const PHP_EOL;
use const STDERR;

/**
 * @no-named-arguments
 */
readonly class OtelStderrLogger implements LoggerInterface
{
    use LoggerTrait;

    /**
     * @var array<int|string, int>
     */
    protected const array LEVELS = [
        'EMERGENCY' => 21,
        'ALERT' => 20,
        'CRITICAL' => 19,
        'ERROR' => 17,
        'WARNING' => 13,
        'NOTICE' => 12,
        'INFO' => 9,
        'DEBUG' => 5,
    ];

    protected readonly ClockInterface $clock;

    public function __construct(ClockInterface $clock)
    {
        $this->clock = $clock;
    }

    public static function unload(ContainerInterface $container): self
    {
        $clock = $container->get(ClockInterface::class);

        assert($clock instanceof ClockInterface);

        return new self($clock);
    }

    #[Override]
    public function log(mixed $level, Stringable|string $message, array $context = []): void
    {
        $level = $this->level($level);
        $severity = $this->severity($level);
        $nano = $this->nano();

        $this->send($this->encode([
            'resourceLogs' => [
                [
                    'scopeLogs' => [
                        [
                            'scope' => [
                                'name' => 'tomaschochola/php-psr-3-logger-interface',
                                'version' => '1.0.0',
                            ],
                            'logRecords' => [
                                [
                                    'timeUnixNano' => $nano,
                                    'severityNumber' => $severity,
                                    'severityText' => $level,
                                    'body' => $this->anyValue($message),
                                    'attributes' => $this->attributes($context),
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]));
    }

    /**
     * @return array<int|string, mixed>
     */
    protected function anyValue(mixed $value): array
    {
        if ($value === null) {
            return [];
        }

        if (is_string($value) || $value instanceof Stringable) {
            return ['stringValue' => (string) $value];
        }

        if (is_bool($value)) {
            return ['boolValue' => $value];
        }

        if (is_int($value)) {
            return ['intValue' => (string) $value];
        }

        if (is_float($value)) {
            return ['doubleValue' => $value];
        }

        if (is_array($value)) {
            if (array_is_list($value)) {
                return [
                    'arrayValue' => [
                        'values' => array_map(fn($val) => $this->anyValue($val), $value),
                    ],
                ];
            }

            $kvs = [];

            foreach ($value as $k => $val) {
                $kvs[] = ['key' => (string) $k, 'value' => $this->anyValue($val)];
            }

            return ['kvlistValue' => ['values' => $kvs]];
        }

        throw new LogicException();
    }

    /**
     * @param array<int|string, mixed> $attributes
     *
     * @return array<int|string, mixed>
     */
    protected function attributes(array $attributes): array
    {
        $kvs = [];

        foreach ($attributes as $k => $v) {
            $kvs[] = [
                'key' => (string) $k,
                'value' => $this->anyValue($v),
            ];
        }

        return $kvs;
    }

    protected function encode(mixed $value): string
    {
        $encoded = json_encode($value, JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE | JSON_PRESERVE_ZERO_FRACTION | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        assert(is_string($encoded));

        return $encoded;
    }

    protected function level(mixed $level): string
    {
        if (!is_string($level) && !$level instanceof Stringable) {
            throw new InvalidArgumentException('$level');
        }

        return mb_strtoupper((string) $level);
    }

    protected function nano(): string
    {
        return $this->clock->now()->format('Uu') . '000';
    }

    protected function send(string $payload): void
    {
        $payload .= PHP_EOL;
        $written = fwrite(STDERR, $payload);

        if ($written !== mb_strlen($payload, '8bit')) {
            throw new UnexpectedValueException('fwrite');
        }
    }

    protected function severity(string $level): int
    {
        $severity = static::LEVELS[$level] ?? null;

        if ($severity === null) {
            throw new InvalidArgumentException('$level');
        }

        return $severity;
    }
}
