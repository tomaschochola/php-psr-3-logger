<?php

declare(strict_types=1);

namespace TomasChochola\Psr\Log;

use NoDiscard;
use Override;
use Psr\Clock\ClockInterface;

/**
 * @no-named-arguments
 */
readonly class Recorder implements RecorderInterface
{
    private ClockInterface $clock;

    public function __construct(ClockInterface $clock)
    {
        $this->clock = $clock;
    }

    #[NoDiscard()]
    #[Override()]
    public function record(string $level, string $template, array $context): RecordInterface
    {
        return new Record($level, $template, $context, $this->clock->now());
    }
}
