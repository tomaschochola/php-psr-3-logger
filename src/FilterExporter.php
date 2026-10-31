<?php

declare(strict_types=1);

namespace TomasChochola\Psr\Log;

use Override;

/**
 * @no-named-arguments
 */
readonly class FilterExporter implements ExporterInterface
{
    private ExporterInterface $exporter;

    private FilterInterface $filter;

    public function __construct(FilterInterface $filter, ExporterInterface $exporter)
    {
        $this->filter = $filter;
        $this->exporter = $exporter;
    }

    #[Override()]
    public function export(RecordInterface $record): void
    {
        if (!$this->filter->allow($record)) {
            return;
        }

        $this->exporter->export($record);
    }
}
