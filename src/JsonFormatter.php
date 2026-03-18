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

use function array_replace;
use function is_string;
use function json_encode;

use const JSON_INVALID_UTF8_SUBSTITUTE;
use const JSON_PARTIAL_OUTPUT_ON_ERROR;
use const JSON_PRESERVE_ZERO_FRACTION;
use const JSON_UNESCAPED_SLASHES;
use const JSON_UNESCAPED_UNICODE;

/**
 * @no-named-arguments
 */
readonly class JsonFormatter implements FormatterInterface
{
    #[NoDiscard]
    public static function inject(ContainerInterface $container): self
    {
        return new self();
    }

    #[NoDiscard]
    #[Override]
    public function format(RecordInterface $record): string
    {
        $timestamp = $record->timestamp->format('Uu') . '000';

        $encoded = json_encode(array_replace((array) $record, [
            'timestamp' => $timestamp,
        ]), JSON_INVALID_UTF8_SUBSTITUTE | JSON_PRESERVE_ZERO_FRACTION | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        if (is_string($encoded)) {
            return $encoded;
        }

        $encoded = json_encode([
            'context' => $record->context,
            'level' => $record->level,
            'message' => $record->message,
            'timestamp' => $timestamp,
        ], JSON_INVALID_UTF8_SUBSTITUTE | JSON_PRESERVE_ZERO_FRACTION | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        if (is_string($encoded)) {
            return $encoded;
        }

        $encoded = json_encode(array_replace((array) $record, [
            'context' => $record->context,
            'level' => $record->level,
            'message' => $record->message,
            'timestamp' => $timestamp,
        ]), JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE | JSON_PRESERVE_ZERO_FRACTION | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        if (is_string($encoded)) {
            return $encoded;
        }

        return "{\"level\":\"{$record->level}\",\"message\":\"{$record->message}\",\"timestamp\":\"{$timestamp}\"}";
    }
}
