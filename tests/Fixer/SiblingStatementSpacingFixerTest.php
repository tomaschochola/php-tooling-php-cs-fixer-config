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

namespace Tests\Fixer;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PhpCsFixer\Tokenizer\Tokens;
use SplFileInfo;
use Tests\TestCase;
use TomasChochola\Tooling\PhpCsFixer\Fixer\SiblingStatementSpacing\Statement;
use TomasChochola\Tooling\PhpCsFixer\Fixer\SiblingStatementSpacingFixer;

/**
 * @internal
 *
 * @no-named-arguments
 */
#[CoversClass(Statement::class)]
#[CoversClass(SiblingStatementSpacingFixer::class)]
#[Small()]
class SiblingStatementSpacingFixerTest extends TestCase
{
    #[Test()]
    public function testCanLeaveSingleLineGroupsUntouched(): void
    {
        $input = <<<'PHP'
            <?php

            function example(): void
            {
                one();

                two();
                three();
                four(
                    1,
                );
                five();
            }

            PHP;

        $expected = <<<'PHP'
            <?php

            function example(): void
            {
                one();

                two();
                three();

                four(
                    1,
                );

                five();
            }

            PHP;

        self::assertSame($expected, self::fix($input, ['separate_single_line_groups' => false]));
    }

    #[Test()]
    public function testGroupsSingleLineStatementsByStatementShape(): void
    {
        $input = <<<'PHP'
            <?php

            function example(object $service, object $other): void
            {
                imported_call();

                global_call();
                $this->boot();

                $this->run();
                $service->start();

                $other->finish();
                Service::make();

                Other::make();
                $value = true;

                $otherValue = false;
                return;
            }

            PHP;

        $expected = <<<'PHP'
            <?php

            function example(object $service, object $other): void
            {
                imported_call();
                global_call();

                $this->boot();
                $this->run();

                $service->start();
                $other->finish();

                Service::make();
                Other::make();

                $value = true;
                $otherValue = false;

                return;
            }

            PHP;

        self::assertSame($expected, self::fix($input));
    }

    #[Test()]
    public function testKeepsClassMembersAndControlContinuationAsSeparateConcerns(): void
    {
        $input = <<<'PHP'
            <?php

            class Demo
            {
                public function a(): void
                {
                }
                public function b(): void
                {
                }
            }

            function demo(bool $enabled): void
            {
                if ($enabled) {
                    one();
                } else {
                    two();
                }
                after();
            }

            PHP;

        $expected = <<<'PHP'
            <?php

            class Demo
            {
                public function a(): void
                {
                }
                public function b(): void
                {
                }
            }

            function demo(bool $enabled): void
            {
                if ($enabled) {
                    one();
                } else {
                    two();
                }

                after();
            }

            PHP;

        self::assertSame($expected, self::fix($input));
    }

    #[Test()]
    public function testSeparatesMultilineStatementsAndCommentLedStatements(): void
    {
        $input = <<<'PHP'
            <?php

            function example(): void
            {
                same();
                other(
                    1,
                );
                third();
                // Explain the next statement.
                fourth();
                /*
                 * Explain the next statement with a multiline comment.
                 */
                fifth();
                sixth();
            }

            PHP;

        $expected = <<<'PHP'
            <?php

            function example(): void
            {
                same();

                other(
                    1,
                );

                third();

                // Explain the next statement.
                fourth();

                /*
                 * Explain the next statement with a multiline comment.
                 */
                fifth();

                sixth();
            }

            PHP;

        self::assertSame($expected, self::fix($input));
    }

    /**
     * @param array<string, bool> $configuration
     */
    private static function fix(string $input, array $configuration = []): string
    {
        $fixer = new SiblingStatementSpacingFixer();

        if ($configuration !== []) {
            $fixer->configure($configuration);
        }

        $tokens = Tokens::fromCode($input);

        $fixer->fix(new SplFileInfo(__FILE__), $tokens);
        $tokens->clearEmptyTokens();

        return $tokens->generateCode();
    }
}
