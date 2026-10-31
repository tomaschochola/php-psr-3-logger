<?php

declare(strict_types=1);

namespace TomasChochola\Psr\Log;

use NoDiscard;

/**
 * @no-named-arguments
 */
interface FilterInterface
{
    #[NoDiscard()]
    public function allow(RecordInterface $record): bool;
}
