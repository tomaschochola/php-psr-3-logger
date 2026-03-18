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

use InvalidArgumentException;
use NoDiscard;
use Override;
use UnexpectedValueException;
use Psr\Container\ContainerInterface;

use function fwrite;
use function is_resource;
use function mb_strlen;

use const PHP_EOL;
use const STDERR;

/**
 * @no-named-arguments
 */
readonly class ResourceWriter implements WriterInterface
{
    #[NoDiscard]
    public static function inject(ContainerInterface $container): self
    {
        return new self();
    }

    /**
     * @var resource
     */
    private readonly mixed $resource;

    /**
     * @param resource $resource
     */
    public function __construct(mixed $resource = STDERR)
    {
        if (!is_resource($resource)) {
            throw new InvalidArgumentException('$resource');
        }

        $this->resource = $resource;
    }

    #[Override]
    public function write(string $payload): void
    {
        $payload .= PHP_EOL;
        $written = fwrite($this->resource, $payload);

        if ($written !== mb_strlen($payload, '8bit')) {
            throw new UnexpectedValueException('fwrite');
        }
    }
}
