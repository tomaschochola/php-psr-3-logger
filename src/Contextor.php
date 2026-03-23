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

use JsonSerializable;
use NoDiscard;
use Override;
use Psr\Container\ContainerInterface;
use Stringable;

use function is_iterable;
use function is_scalar;
use function iterator_to_array;

/**
 * @no-named-arguments
 */
readonly class Contextor implements ContextorInterface
{
    #[NoDiscard]
    public static function inject(ContainerInterface $container): self
    {
        return new self();
    }

    #[NoDiscard]
    #[Override]
    public function process(RecordInterface $record): RecordInterface
    {
        return $record->withStructured(iterator_to_array(self::iterate($record->context)));
    }

    #[NoDiscard]
    private static function accepted(mixed $current): bool
    {
        return $current === null || is_scalar($current) || $current instanceof JsonSerializable || $current instanceof Stringable;
    }

    /**
     * @param iterable<mixed, mixed> $context
     * @return iterable<mixed, mixed>
     */
    #[NoDiscard]
    private static function iterate(iterable $context): iterable
    {
        foreach ($context as $key => $value) {
            if (is_iterable($value)) {
                $structured = iterator_to_array(self::iterate($value));

                if ($structured !== []) {
                    yield $key => $structured;
                }
            } else if (self::accepted($value)) {
                yield $key => $value;
            }
        }
    }
}
