<?php

/**
 * @author Tomáš Chochola <tomaschochola@tomaschochola.cz>
 * @copyright © 2026 Tomáš Chochola <tomaschochola@tomaschochola.cz>
 *
 * @license CC-BY-ND-4.0
 *
 * @see {@link https://creativecommons.org/licenses/by-nd/4.0/} License
 * @see {@link https://github.com/tomaschochola} GitHub Profile
 * @see {@link https://github.com/sponsors/tomaschochola} GitHub Sponsors
 */

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
