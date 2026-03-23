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
use Override;

/**
 * @no-named-arguments
 */
readonly class Record implements RecordInterface
{
    #[Override]
    public readonly array $context;

    #[Override]
    public readonly int $code;

    #[Override]
    public readonly string $level;

    #[Override]
    public readonly string $message;

    #[Override]
    public readonly array $structured;

    #[Override]
    public readonly string $template;

    #[Override]
    public readonly DateTimeImmutable $timestamp;

    /**
     * @param array<mixed, mixed> $context
     * @param array<mixed, mixed> $structured
     */
    public function __construct(array $context, int $code, string $level, string $template, DateTimeImmutable $timestamp, string $message = '', array $structured = [])
    {
        $this->context = $context;
        $this->code = $code;
        $this->level = $level;
        $this->template = $template;
        $this->timestamp = $timestamp;
        $this->message = $message;
        $this->structured = $structured;
    }

    #[NoDiscard]
    #[Override]
    public function withMessage(string $message): static
    {
        return clone ($this, ['message' => $message]);
    }

    /**
     * @param array<mixed, mixed> $structured
     */
    #[NoDiscard]
    #[Override]
    public function withStructured(array $structured): static
    {
        return clone ($this, ['structured' => $structured]);
    }
}
