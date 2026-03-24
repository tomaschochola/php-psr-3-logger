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
use Psr\Clock\ClockInterface;
use Psr\Container\ContainerInterface;

use function assert;

/**
 * @no-named-arguments
 */
readonly class Recorder implements RecorderInterface
{
    private readonly ClockInterface $clock;

    public function __construct(ClockInterface $clock)
    {
        $this->clock = $clock;
    }

    #[NoDiscard]
    public static function inject(ContainerInterface $container): self
    {
        $clock = $container->get(ClockInterface::class);

        assert($clock instanceof ClockInterface);

        return new self($clock);
    }

    #[NoDiscard]
    #[Override]
    public function record(string $level, string $template, array $context): RecordInterface
    {
        return new Record($level, $template, $context, $this->clock->now());
    }
}
