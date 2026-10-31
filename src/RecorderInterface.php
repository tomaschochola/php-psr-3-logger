<?php

declare(strict_types=1);

namespace TomasChochola\Psr\Log;

use NoDiscard;

/**
 * @no-named-arguments
 */
interface RecorderInterface
{
    /**
     * @param array<mixed, mixed> $context
     */
    #[NoDiscard()]
    public function record(string $level, string $template, array $context): RecordInterface;
}
