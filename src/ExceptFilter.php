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

use function in_array;

/**
 * @no-named-arguments
 */
readonly class ExceptFilter implements FilterInterface
{
    /**
     * @var array<mixed, string>
     */
    private array $levels;

    /**
     * @param array<mixed, string> $levels
     */
    public function __construct(array $levels)
    {
        $this->levels = $levels;
    }

    #[NoDiscard()]
    #[Override()]
    public function allow(RecordInterface $record): bool
    {
        return !in_array($record->level, $this->levels, true);
    }
}
