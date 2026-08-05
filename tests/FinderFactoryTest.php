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
use SplFileInfo;
use TomasChochola\Tooling\PhpCsFixer\FinderFactory;

use function array_map;
use function dirname;
use function iterator_to_array;

/**
 * @internal
 *
 * @no-named-arguments
 */
#[CoversClass(FinderFactory::class)]
#[Small()]
class FinderFactoryTest extends TestCase
{
    #[Test()]
    public function testCreatesFinderWithRepositoryIgnorePolicy(): void
    {
        $files = FinderFactory::create()
            ->files()
            ->in(dirname(__DIR__));

        $paths = array_map(static fn(SplFileInfo $file): string => $file->getRelativePathname(), iterator_to_array($files, false));

        self::assertContains('.php-cs-fixer.php', $paths);
        self::assertNotContains('vendor/autoload.php', $paths);
    }
}
