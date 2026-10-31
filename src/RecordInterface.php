<?php

declare(strict_types=1);

namespace TomasChochola\Psr\Log;

use DateTimeImmutable;

/**
 * @no-named-arguments
 */
interface RecordInterface
{
    /**
     * @var array<mixed, mixed>
     */
    public array $context { get; }

    public string $level { get; }

    public string $template { get; }

    public DateTimeImmutable $timestamp { get; }
}
