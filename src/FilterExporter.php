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
