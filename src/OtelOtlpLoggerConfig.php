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

use Override;
use Psr\Http\Message\UriInterface;

/**
 * @no-named-arguments
 */
readonly class OtelOtlpLoggerConfig implements OtelOtlpLoggerConfigInterface
{
    #[Override]
    public readonly string $method;

    #[Override]
    public readonly UriInterface|string $uri;

    public function __construct(UriInterface|string $uri = 'http://localhost:4318/v1/logs', string $method = 'POST')
    {
        $this->uri = $uri;
        $this->method = $method;
    }
}
