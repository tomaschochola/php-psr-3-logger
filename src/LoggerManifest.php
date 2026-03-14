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

use IteratorAggregate;
use NoDiscard;
use Override;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Traversable;

/**
 * @no-named-arguments
 *
 * @implements IteratorAggregate<mixed, mixed>
 */
readonly class LoggerManifest implements IteratorAggregate
{
    #[NoDiscard]
    #[Override]
    public function getIterator(): Traversable
    {
        yield StructuredRecorder::class => [StructuredRecorderAssembler::class, 'assemble'];

        yield RecorderInterface::class => [StructuredRecorderAssembler::class, 'assemble'];

        yield JsonFormatter::class => [JsonFormatterAssembler::class, 'assemble'];

        yield FormatterInterface::class => [JsonFormatterAssembler::class, 'assemble'];

        yield ResourceWriter::class => [ResourceWriterAssembler::class, 'assemble'];

        yield WriterInterface::class => [ResourceWriterAssembler::class, 'assemble'];

        yield FormatterWriterExporter::class => [FormatterWriterExporterAssembler::class, 'assemble'];

        yield ExporterInterface::class => [FormatterWriterExporterAssembler::class, 'assemble'];

        yield Logger::class => [LoggerAssembler::class, 'assemble'];

        yield NullLogger::class => [NullLoggerAssembler::class, 'assemble'];

        yield LoggerInterface::class => [LoggerAssembler::class, 'assemble'];
    }
}
