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

use NoDiscard;
use Override;
use Psr\Container\ContainerInterface;

use function assert;

/**
 * @no-named-arguments
 */
readonly class FormatterWriterExporter implements ExporterInterface
{
    private readonly FormatterInterface $formatter;

    private readonly WriterInterface $writer;

    public function __construct(FormatterInterface $formatter, WriterInterface $writer)
    {
        $this->formatter = $formatter;
        $this->writer = $writer;
    }

    #[NoDiscard]
    public static function inject(ContainerInterface $container): self
    {
        $formatter = $container->get(FormatterInterface::class);
        $writer = $container->get(WriterInterface::class);

        assert($formatter instanceof FormatterInterface);
        assert($writer instanceof WriterInterface);

        return new self($formatter, $writer);
    }

    #[Override]
    public function export(RecordInterface $record): void
    {
        $this->writer->write($this->formatter->format($record));
    }
}
