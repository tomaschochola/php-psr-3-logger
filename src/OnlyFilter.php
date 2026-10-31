<?php

declare(strict_types=1);

namespace TomasChochola\Psr\Log;

use NoDiscard;
use Override;

use function in_array;

/**
 * @no-named-arguments
 */
readonly class OnlyFilter implements FilterInterface
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
        return in_array($record->level, $this->levels, true);
    }
}
