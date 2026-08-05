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

namespace Tests;

use DateTimeImmutable;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use Psr\Clock\ClockInterface;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LogLevel;
use TomasChochola\Psr\Log\CollectingExporter;
use TomasChochola\Psr\Log\ExceptFilter;
use TomasChochola\Psr\Log\FanoutExporter;
use TomasChochola\Psr\Log\FilterExporter;
use TomasChochola\Psr\Log\FormatterWriterExporter;
use TomasChochola\Psr\Log\Interpolator;
use TomasChochola\Psr\Log\JsonFormatter;
use TomasChochola\Psr\Log\Logger;
use TomasChochola\Psr\Log\OnlyFilter;
use TomasChochola\Psr\Log\Record;
use TomasChochola\Psr\Log\RecordInterface;
use TomasChochola\Psr\Log\Recorder;
use TomasChochola\Psr\Log\ResourceWriter;

use function fclose;
use function fopen;
use function json_decode;
use function rewind;
use function stream_get_contents;

use const JSON_THROW_ON_ERROR;

/**
 * @internal
 *
 * @no-named-arguments
 */
#[CoversClass(CollectingExporter::class)]
#[CoversClass(ExceptFilter::class)]
#[CoversClass(FanoutExporter::class)]
#[CoversClass(FilterExporter::class)]
#[CoversClass(FormatterWriterExporter::class)]
#[CoversClass(Interpolator::class)]
#[CoversClass(JsonFormatter::class)]
#[CoversClass(Logger::class)]
#[CoversClass(OnlyFilter::class)]
#[CoversClass(Record::class)]
#[CoversClass(Recorder::class)]
#[CoversClass(ResourceWriter::class)]
#[Small()]
final class LoggingPipelineTest extends TestCase
{
    #[Test()]
    public function fanoutAppliesOnlyAndExceptFilters(): void
    {
        $record = new Record(LogLevel::INFO, 'Message', [], new DateTimeImmutable());
        $included = new CollectingExporter();
        $excluded = new CollectingExporter();

        $exporter = new FanoutExporter([
            new FilterExporter(new OnlyFilter([LogLevel::INFO]), $included),
            new FilterExporter(new ExceptFilter([LogLevel::INFO]), $excluded),
        ]);

        $exporter->export($record);

        self::assertCount(1, $included->collection);
        self::assertCount(0, $excluded->collection);
    }

    #[Test()]
    public function formatterWriterExportsInterpolatedJsonLine(): void
    {
        $timestamp = new DateTimeImmutable('2026-01-02T03:04:05.123456+00:00');
        $record = new Record(LogLevel::INFO, 'User {id}', ['id' => 42], $timestamp);
        $resource = fopen('php://memory', 'w+b');

        self::assertIsResource($resource);

        $exporter = new FormatterWriterExporter(new JsonFormatter(new Interpolator()), new ResourceWriter($resource));
        $exporter->export($record);
        rewind($resource);
        $payload = stream_get_contents($resource);
        fclose($resource);

        self::assertIsString($payload);

        self::assertSame([
            'level' => LogLevel::INFO,
            'message' => 'User 42',
            'context' => ['id' => 42],
            'template' => 'User {id}',
            'timestamp' => '1767323045123456000',
        ], json_decode($payload, true, 512, JSON_THROW_ON_ERROR));
    }

    #[Test()]
    public function loggerRecordsAndExportsStructuredContext(): void
    {
        $timestamp = new DateTimeImmutable('2026-01-02T03:04:05.123456+00:00');
        $exporter = new CollectingExporter();
        $logger = new Logger(new Recorder(new FixedClock($timestamp)), $exporter);

        $logger->info('User {id}', ['id' => 42]);

        self::assertCount(1, $exporter->collection);
        $record = $exporter->collection[0];
        self::assertInstanceOf(RecordInterface::class, $record);
        self::assertSame(LogLevel::INFO, $record->level);
        self::assertSame('User {id}', $record->template);
        self::assertSame(['id' => 42], $record->context);
        self::assertSame($timestamp, $record->timestamp);
    }

    #[Test()]
    public function loggerRejectsUnknownLevels(): void
    {
        $logger = new Logger(new Recorder(new FixedClock(new DateTimeImmutable())), new CollectingExporter());

        $this->expectException(InvalidArgumentException::class);

        $logger->log('invalid', 'Message');
    }
}

/**
 * @internal
 *
 * @no-named-arguments
 */
final readonly class FixedClock implements ClockInterface
{
    public function __construct(private DateTimeImmutable $timestamp)
    {
    }

    #[Override()]
    public function now(): DateTimeImmutable
    {
        return $this->timestamp;
    }
}
