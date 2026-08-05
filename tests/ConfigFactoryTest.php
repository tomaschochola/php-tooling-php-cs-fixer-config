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

namespace Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PhpCsFixer\Finder;
use TomasChochola\Tooling\PhpCsFixer\ConfigFactory;
use TomasChochola\Tooling\PhpCsFixer\Fixer\SiblingStatementSpacingFixer;

/**
 * @internal
 *
 * @no-named-arguments
 */
#[CoversClass(ConfigFactory::class)]
#[Small()]
#[UsesClass(SiblingStatementSpacingFixer::class)]
class ConfigFactoryTest extends TestCase
{
    #[Test()]
    public function testCreatesConfiguration(): void
    {
        $finder = self::createStub(Finder::class);
        $rules = ['strict_param' => true];
        $config = ConfigFactory::create($finder, $rules);

        self::assertSame($finder, $config->getFinder());
        self::assertSame('    ', $config->getIndent());
        self::assertSame("\n", $config->getLineEnding());
        self::assertTrue($config->getRiskyAllowed());
        self::assertSame($rules, $config->getRules());
        self::assertCount(1, $config->getCustomFixers());
        self::assertContainsOnlyInstancesOf(SiblingStatementSpacingFixer::class, $config->getCustomFixers());
    }
}
