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

use DateTimeImmutable;
use Override;

/**
 * @no-named-arguments
 */
readonly class Record implements RecordInterface
{
    #[Override()]
    public array $context;

    #[Override()]
    public string $level;

    #[Override()]
    public string $template;

    #[Override()]
    public DateTimeImmutable $timestamp;

    /**
     * @param array<mixed, mixed> $context
     */
    public function __construct(string $level, string $template, array $context, DateTimeImmutable $timestamp)
    {
        $this->context = $context;
        $this->level = $level;
        $this->template = $template;
        $this->timestamp = $timestamp;
    }
}
