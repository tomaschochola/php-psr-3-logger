<?php

declare(strict_types=1);

namespace TomasChochola\Psr\Log;

/**
 * @no-named-arguments
 */
interface ExporterInterface
{
    public function export(RecordInterface $record): void;
}
