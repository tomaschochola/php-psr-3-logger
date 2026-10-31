<?php

declare(strict_types=1);

namespace TomasChochola\Psr\Log;

use Override;

/**
 * @no-named-arguments
 */
readonly class FanoutExporter implements ExporterInterface
{
    /**
     * @var iterable<mixed, ExporterInterface>
     */
    private iterable $exporters;

    /**
     * @param iterable<mixed, ExporterInterface> $exporters
     */
    public function __construct(iterable $exporters)
    {
        $this->exporters = $exporters;
    }

    #[Override()]
    public function export(RecordInterface $record): void
    {
        foreach ($this->exporters as $exporter) {
            $exporter->export($record);
        }
    }
}
