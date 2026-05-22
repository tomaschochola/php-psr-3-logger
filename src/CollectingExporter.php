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

use ArrayIterator;
use Override;

/**
 * @no-named-arguments
 */
readonly class CollectingExporter implements ExporterInterface
{
    /**
     * @var ArrayIterator<int, RecordInterface>
     */
    public ArrayIterator $collection;

    public function __construct()
    {
        $this->collection = new ArrayIterator();
    }

    #[Override()]
    public function export(RecordInterface $record): void
    {
        $this->collection->append($record);
    }
}
