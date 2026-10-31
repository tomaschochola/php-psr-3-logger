<?php

declare(strict_types=1);

namespace TomasChochola\Psr\Log;

use NoDiscard;

/**
 * @no-named-arguments
 */
interface InterpolatorInterface
{
    #[NoDiscard()]
    public function interpolate(RecordInterface $record): string;
}
