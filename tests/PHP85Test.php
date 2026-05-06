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
use TomasChochola\Tooling\PhpCsFixer\PHP85;

/**
 * @internal
 *
 * @no-named-arguments
 */
#[CoversClass(PHP85::class)]
#[Small]
final class PHP85Test extends TestCase
{
    #[Test]
    public function testStrictRules(): void
    {
        self::assertNotEmpty(PHP85::strictRules());
    }

    #[Test]
    public function testProjectRuleOverrides(): void
    {
        self::assertNotEmpty(PHP85::projectRuleOverrides());
    }
}
