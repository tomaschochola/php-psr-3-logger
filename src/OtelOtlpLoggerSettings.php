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
use Psr\Container\ContainerInterface;
use Psr\Http\Message\UriInterface;

use function getenv;
use function is_string;

/**
 * @no-named-arguments
 */
readonly class OtelOtlpLoggerSettings implements OtelOtlpLoggerSettingsInterface
{
    private const string DEFAULT_METHOD = 'POST';

    private const string DEFAULT_URI = 'http://localhost:4318/v1/logs';

    #[Override]
    public readonly string $method;

    #[Override]
    public readonly UriInterface|string $uri;

    public function __construct(UriInterface|string $uri = self::DEFAULT_URI, string $method = self::DEFAULT_METHOD)
    {
        $this->uri = $uri;
        $this->method = $method;
    }

    public static function unload(ContainerInterface $container): self
    {
        $uri = getenv('OTLP_URI');
        $method = getenv('OTLP_METHOD');

        if (!is_string($uri) || $uri === '') {
            $uri = self::DEFAULT_URI;
        }

        if (!is_string($method) || $method === '') {
            $method = self::DEFAULT_METHOD;
        }

        return new self($uri, $method);
    }
}
