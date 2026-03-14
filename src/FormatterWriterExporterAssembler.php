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
use Psr\Container\ContainerInterface;

use function assert;

/**
 * @no-named-arguments
 */
readonly class FormatterWriterExporterAssembler
{
    #[NoDiscard]
    public static function assemble(ContainerInterface $container): FormatterWriterExporter
    {
        $formatter = $container->get(FormatterInterface::class);
        $writer = $container->get(WriterInterface::class);

        assert($formatter instanceof FormatterInterface);
        assert($writer instanceof WriterInterface);

        return new FormatterWriterExporter($formatter, $writer);
    }
}
