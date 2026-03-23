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

use NoDiscard;
use Override;
use Psr\Container\ContainerInterface;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LogLevel;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Stringable;

use function assert;

/**
 * @no-named-arguments
 */
readonly class Logger implements LoggerInterface
{
    use LoggerTrait;

    private readonly ExporterInterface $exporter;

    private readonly RecorderInterface $recorder;

    public function __construct(RecorderInterface $recorder, ExporterInterface $exporter)
    {
        $this->recorder = $recorder;
        $this->exporter = $exporter;
    }

    #[NoDiscard]
    public static function inject(ContainerInterface $container): self
    {
        $exporter = $container->get(ExporterInterface::class);
        $recorder = $container->get(RecorderInterface::class);

        assert($exporter instanceof ExporterInterface);
        assert($recorder instanceof RecorderInterface);

        return new self($recorder, $exporter);
    }

    #[Override]
    public function log(mixed $level, Stringable|string $message, array $context = []): void
    {
        [$code, $level] = match ($level) {
            LogLevel::EMERGENCY => [0, LogLevel::EMERGENCY],
            LogLevel::ALERT => [1, LogLevel::ALERT],
            LogLevel::CRITICAL => [2, LogLevel::CRITICAL],
            LogLevel::ERROR => [3, LogLevel::ERROR],
            LogLevel::WARNING => [4, LogLevel::WARNING],
            LogLevel::NOTICE => [5, LogLevel::NOTICE],
            LogLevel::INFO => [6, LogLevel::INFO],
            LogLevel::DEBUG => [7, LogLevel::DEBUG],
            default => throw new InvalidArgumentException('$level'),
        };

        $record = $this->recorder->record((string) $message, $level, $code, $context);

        $this->exporter->export($record);
    }
}
