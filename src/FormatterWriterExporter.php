<?php

declare(strict_types=1);

namespace TomasChochola\Psr\Log;

use Override;

/**
 * @no-named-arguments
 */
readonly class FormatterWriterExporter implements ExporterInterface
{
    private FormatterInterface $formatter;

    private WriterInterface $writer;

    public function __construct(FormatterInterface $formatter, WriterInterface $writer)
    {
        $this->formatter = $formatter;
        $this->writer = $writer;
    }

    #[Override()]
    public function export(RecordInterface $record): void
    {
        $this->writer->write($this->formatter->format($record));
    }
}
