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
        $level = match ($level) {
            LogLevel::EMERGENCY => LogLevel::EMERGENCY,
            LogLevel::ALERT => LogLevel::ALERT,
            LogLevel::CRITICAL => LogLevel::CRITICAL,
            LogLevel::ERROR => LogLevel::ERROR,
            LogLevel::WARNING => LogLevel::WARNING,
            LogLevel::NOTICE => LogLevel::NOTICE,
            LogLevel::INFO => LogLevel::INFO,
            LogLevel::DEBUG => LogLevel::DEBUG,
            default => throw new InvalidArgumentException('$level'),
        };

        $record = $this->recorder->record($level, (string) $message, $context);

        $this->exporter->export($record);
    }
}
