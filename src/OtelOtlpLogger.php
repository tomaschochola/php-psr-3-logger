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
use Psr\Clock\ClockInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

use function assert;

/**
 * @no-named-arguments
 */
readonly class OtelOtlpLogger extends OtelStderrLogger
{
    protected readonly OtelOtlpLoggerSettingsInterface $config;

    protected readonly ClientInterface $httpClient;

    protected readonly RequestFactoryInterface $requestFactory;

    protected readonly StreamFactoryInterface $streamFactory;

    public function __construct(ClockInterface $clock, StreamFactoryInterface $streamFactory, RequestFactoryInterface $requestFactory, ClientInterface $httpClient, OtelOtlpLoggerSettingsInterface $config = new OtelOtlpLoggerSettings())
    {
        parent::__construct($clock);

        $this->streamFactory = $streamFactory;
        $this->requestFactory = $requestFactory;
        $this->httpClient = $httpClient;
        $this->config = $config;
    }

    #[Override]
    public static function unload(ContainerInterface $container): self
    {
        $clock = $container->get(ClockInterface::class);
        $streamFactory = $container->get(StreamFactoryInterface::class);
        $requestFactory = $container->get(RequestFactoryInterface::class);
        $httpClient = $container->get(ClientInterface::class);
        $config = $container->get(OtelOtlpLoggerSettingsInterface::class);

        assert($clock instanceof ClockInterface);
        assert($streamFactory instanceof StreamFactoryInterface);
        assert($requestFactory instanceof RequestFactoryInterface);
        assert($httpClient instanceof ClientInterface);
        assert($config instanceof OtelOtlpLoggerSettingsInterface);

        return new self($clock, $streamFactory, $requestFactory, $httpClient, $config);
    }

    #[Override]
    public function send(string $payload): void
    {
        $stream = $this->streamFactory->createStream($payload);

        $request = $this->requestFactory->createRequest($this->config->method, $this->config->uri)
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withBody($stream);

        $this->httpClient->sendRequest($request);
    }
}
