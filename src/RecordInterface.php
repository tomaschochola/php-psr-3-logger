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
use NoDiscard;

/**
 * @no-named-arguments
 */
interface RecordInterface
{
    /**
     * @var array<mixed, mixed>
     */
    public array $context { get; }

    public int $code { get; }

    public string $level { get; }

    public string $message { get; }

    /**
     * @var array<mixed, mixed>
     */
    public array $structured { get; }

    public string $template { get; }

    public DateTimeImmutable $timestamp { get; }

    #[NoDiscard]
    public function withMessage(string $message): static;

    /**
     * @param array<mixed, mixed> $structured
     */
    #[NoDiscard]
    public function withStructured(array $structured): static;
}
