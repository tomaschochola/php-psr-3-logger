<?php

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
