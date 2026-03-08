<?php

declare(strict_types=1);

namespace TomasChochola\Psr\Log;

use IteratorAggregate;
use NoDiscard;
use Override;
use Psr\Log\LoggerInterface;
use Traversable;

/**
 * @no-named-arguments
 *
 * @implements IteratorAggregate<mixed, mixed>
 */
readonly class LoggerProvider implements IteratorAggregate
{
    #[NoDiscard]
    #[Override]
    public function getIterator(): Traversable
    {
        yield OtelStderrLogger::class => [OtelStderrLogger::class, 'unload'];
        yield LoggerInterface::class => [OtelStderrLogger::class, 'unload'];
        yield OtelOtlpLogger::class => [OtelOtlpLogger::class, 'unload'];
        yield OtelOtlpLoggerSettings::class => [OtelOtlpLoggerSettings::class, 'unload'];
        yield OtelOtlpLoggerSettingsInterface::class => [OtelOtlpLoggerSettings::class, 'unload'];
    }
}
