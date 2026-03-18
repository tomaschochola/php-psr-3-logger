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
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use TomasChochola\Psr\Log\JsonFormatter;
use TomasChochola\Psr\Log\Record;
use TomasChochola\Psr\Log\RecordInterface;

/**
 * @internal
 *
 * @no-named-arguments
 */
#[CoversClass(JsonFormatter::class)]
#[Small]
final class JsonFormatterTest extends TestCase
{
    #[Test]
    public function testFormatsBuiltInRecord(): void
    {
        $timestamp = new DateTimeImmutable('2026-03-18T10:20:30.123456+00:00');
        $record = new Record(
            [
                'count' => 3,
                'nested' => [
                    'foo' => 'bar',
                ],
            ],
            'info',
            'Hello',
            $timestamp,
        );

        /** @var array{context: array<mixed, mixed>, level: string, message: string, timestamp: string} $data */
        $data = json_decode((new JsonFormatter())->format($record), true, 512, JSON_THROW_ON_ERROR);

        self::assertSame([
            'context' => [
                'count' => 3,
                'nested' => [
                    'foo' => 'bar',
                ],
            ],
            'level' => 'info',
            'message' => 'Hello',
            'timestamp' => $timestamp->format('Uu') . '000',
        ], $data);
    }

    #[Test]
    public function testFormatsCustomRecordWithoutPrivateState(): void
    {
        $timestamp = new DateTimeImmutable('2026-03-18T10:20:30.123456+00:00');
        $record = new FixtureRecord(
            [
                'count' => 3,
            ],
            'warning',
            'Custom message',
            $timestamp,
        );

        /** @var array{context: array<mixed, mixed>, level: string, message: string, timestamp: string} $data */
        $data = json_decode((new JsonFormatter())->format($record), true, 512, JSON_THROW_ON_ERROR);

        self::assertSame([
            'context',
            'level',
            'message',
            'timestamp',
        ], array_keys($data));
        self::assertSame([
            'count' => 3,
        ], $data['context']);
        self::assertSame('warning', $data['level']);
        self::assertSame('Custom message', $data['message']);
        self::assertSame($timestamp->format('Uu') . '000', $data['timestamp']);
    }
}

/**
 * @internal
 *
 * @no-named-arguments
 */
final class FixtureRecord implements RecordInterface
{
    public readonly array $context;

    public readonly string $level;

    public readonly string $message;

    public readonly DateTimeImmutable $timestamp;

    private readonly string $internalState;

    /**
     * @param array<mixed, mixed> $context
     */
    public function __construct(array $context, string $level, string $message, DateTimeImmutable $timestamp)
    {
        $this->context = $context;
        $this->level = $level;
        $this->message = $message;
        $this->timestamp = $timestamp;
        $this->internalState = 'hidden';
    }
}
