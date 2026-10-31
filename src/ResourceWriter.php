<?php

declare(strict_types=1);

namespace TomasChochola\Psr\Log;

use Override;
use UnexpectedValueException;

use function fwrite;
use function mb_strlen;

use const PHP_EOL;

/**
 * @no-named-arguments
 */
readonly class ResourceWriter implements WriterInterface
{
    /**
     * @var resource
     */
    private mixed $resource;

    /**
     * @param resource $resource
     */
    public function __construct(mixed $resource)
    {
        $this->resource = $resource;
    }

    #[Override()]
    public function write(string $payload): void
    {
        $payload .= PHP_EOL;
        $written = fwrite($this->resource, $payload);

        if ($written !== mb_strlen($payload, '8bit')) {
            throw new UnexpectedValueException('fwrite');
        }
    }
}
