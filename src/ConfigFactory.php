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

namespace TomasChochola\Tooling\PhpCsFixer;

use PhpCsFixer\Config;
use PhpCsFixer\ConfigInterface;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;
use TomasChochola\Tooling\PhpCsFixer\Fixer\SiblingStatementSpacingFixer;

/**
 * @no-named-arguments
 */
final class ConfigFactory
{
    private function __construct()
    {
    }

    /**
     * @param array<string, array<string, mixed>|bool> $rules
     */
    public static function create(Finder $finder, array $rules): ConfigInterface
    {
        $config = new Config();

        $config->registerCustomFixers([
            new SiblingStatementSpacingFixer(),
        ]);

        $config->setFinder($finder);
        $config->setIndent('    ');
        $config->setLineEnding("\n");
        $config->setParallelConfig(ParallelConfigFactory::detect());
        $config->setRiskyAllowed(true);
        $config->setRules($rules);

        return $config;
    }
}
