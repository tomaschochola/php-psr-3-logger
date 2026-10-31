<?php

declare(strict_types=1);

namespace TomasChochola\Psr\Log;

use NoDiscard;

/**
 * @no-named-arguments
 */
interface FormatterInterface
{
    #[NoDiscard()]
    public function format(RecordInterface $record): string;
}
