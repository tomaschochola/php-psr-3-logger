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
use TomasChochola\Psr\Log\CollectingExporter;
use TomasChochola\Psr\Log\ExceptFilter;
use TomasChochola\Psr\Log\FilterExporter;
use TomasChochola\Psr\Log\OnlyFilter;
use TomasChochola\Psr\Log\Record;

/**
 * @internal
 * @no-named-arguments
 */
#[CoversClass(ExceptFilter::class)]
#[CoversClass(FilterExporter::class)]
#[CoversClass(OnlyFilter::class)]
#[Small]
final class FilterTest extends TestCase
{
    #[Test]
    public function testExceptFilter(): void
    {
        $exporter = new CollectingExporter();
        $filter = new ExceptFilter(['debug']);
        $export = new FilterExporter($filter, $exporter);
        $allow = new Record('error', 'allow', [], new DateTimeImmutable());
        $deny = new Record('debug', 'deny', [], new DateTimeImmutable());

        $export->export($allow);
        $export->export($deny);

        self::assertCount(1, $exporter->collection);
        self::assertSame($allow, $exporter->collection[0]);
    }

    #[Test]
    public function testOnlyFilter(): void
    {
        $exporter = new CollectingExporter();
        $filter = new OnlyFilter(['error']);
        $export = new FilterExporter($filter, $exporter);
        $allow = new Record('error', 'allow', [], new DateTimeImmutable());
        $deny = new Record('debug', 'deny', [], new DateTimeImmutable());

        $export->export($allow);
        $export->export($deny);

        self::assertCount(1, $exporter->collection);
        self::assertSame($allow, $exporter->collection[0]);
    }
}
