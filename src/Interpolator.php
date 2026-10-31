<?php

declare(strict_types=1);

namespace TomasChochola\Psr\Log;

use NoDiscard;
use Override;
use Stringable;

use function is_scalar;
use function strtr;

/**
 * @no-named-arguments
 */
readonly class Interpolator implements InterpolatorInterface
{
    #[NoDiscard()]
    #[Override()]
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
