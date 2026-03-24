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
use Stringable;

use function is_scalar;
use function strtr;

/**
 * @no-named-arguments
 */
readonly class Interpolator implements InterpolatorInterface
{
    #[NoDiscard]
    public static function inject(ContainerInterface $container): self
    {
        return new self();
    }

    #[NoDiscard]
    #[Override]
    public function interpolate(RecordInterface $record): string
    {
        $replace = [];

        foreach ($record->context as $key => $val) {
            if (is_scalar($val) || $val === null || $val instanceof Stringable) {
                $replace['{' . $key . '}'] = (string) $val;
            }
        }

        return strtr($record->template, $replace);
    }
}
