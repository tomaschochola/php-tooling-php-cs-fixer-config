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
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PhpCsFixer\Finder;
use TomasChochola\Tooling\PhpCsFixerConfig\ConfigMaker;

/**
 * @internal
 *
 * @no-named-arguments
 */
#[CoversClass(ConfigMaker::class)]
#[Small]
class ConfigFactoryTest extends TestCase
{
    #[DoesNotPerformAssertions]
    #[Test]
    public function testMake(): void
    {
        ConfigMaker::make(self::createStub(Finder::class), []);
    }
}
