<?php

declare(strict_types=1);

namespace TomasChochola\Psr\Log;

/**
 * @no-named-arguments
 */
interface WriterInterface
{
    public function write(string $payload): void;
}
