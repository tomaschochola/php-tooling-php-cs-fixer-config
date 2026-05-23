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
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PhpCsFixer\Tokenizer\Tokens;
use SplFileInfo;
use Tests\TestCase;
use TomasChochola\Tooling\PhpCsFixer\Fixer\SiblingStatementSpacing\Statement;
use TomasChochola\Tooling\PhpCsFixer\Fixer\SiblingStatementSpacingFixer;

use function explode;
use function implode;

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
    public function testCanLeaveCommentLedStatementsUntouched(): void
    {
        $input = <<<'PHP'
            <?php

            function example(): void
            {
                first();
                // Explain second.
                second();
            }

            PHP;

        self::assertSame($input, self::fix($input, ['separate_comment_led_statements' => false]));
    }

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
                \Vendor\helper();
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
                \Vendor\helper();

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

    #[DataProvider('provideKeepsSameVisualStatementGroupsTogetherCases')]
    #[Test()]
    public function testKeepsSameVisualStatementGroupsTogether(string $left, string $right): void
    {
        self::assertFunctionBodyStatementSpacing($left, $right, false);
    }

    /**
     * @return iterable<string, array{0: string, 1: string}>
     */
    public static function provideKeepsSameVisualStatementGroupsTogetherCases(): iterable
    {
        yield 'function calls' => ['imported_call();', '\Vendor\helper();'];
        yield 'function-like condition constructs' => ['isset($value);', 'empty($value);'];
        yield 'function-like side-effect constructs' => ['unset($value);', 'eval($code);'];
        yield 'this method calls' => ['$this->first();', '$this?->second();'];
        yield 'object method calls' => ['$service->first();', '$other?->second();'];
        yield 'static calls' => ['self::first();', '$class::second();'];
        yield 'assignments' => ['$first = 1;', '$second += 2;'];
        yield 'destructuring assignments' => ['[$first] = $items;', 'list($second) = $items;'];
        yield 'mutations' => ['++$counter;', '$counter--;'];
        yield 'control structures' => ['match ($value) { default => null };', 'if ($condition) {}'];
        yield 'terminal statements' => ['yield $value;', 'return;'];
        yield 'include statements' => ['include $file;', 'require_once $file;'];
        yield 'output statements' => ['echo $value;', 'print $value;'];
        yield 'declarations' => ['function local_first(): void {}', 'class LocalSecond {}'];
        yield 'object lifecycle expressions' => ['new First();', 'clone $object;'];
        yield 'variable declarations' => ['global $global;', 'static $local;'];
        yield 'closures' => ['function (): void {};', 'fn (): null => null;'];
        yield 'labels' => ['first:', 'second:'];
    }

    #[Test()]
    public function testKeepsSingleLineCommentBlocksAttachedToTheirStatement(): void
    {
        $input = <<<'PHP'
            <?php

            function example(): void
            {
                first();
                // Explain second.

                // Explain more.

                second();
                third();
                /*
                 * Explain fourth.
                 */

                fourth();
            }

            PHP;

        $expected = <<<'PHP'
            <?php

            function example(): void
            {
                first();

                // Explain second.

                // Explain more.
                second();
                third();

                /*
                 * Explain fourth.
                 */
                fourth();
            }

            PHP;

        self::assertSame($expected, self::fix($input));
    }

    #[Test()]
    public function testLeavesFileLevelAndNamespaceImportSpacingToNativeFixers(): void
    {
        $fileLevelInput = <<<'PHP'
            <?php

            use App\First;

            use App\Second;
            use function App\third;

            PHP;

        $namespaceInput = <<<'PHP'
            <?php

            namespace Example {
                use App\Fourth;

                use App\Fifth;
                use function App\sixth;
            }

            PHP;

        self::assertSame($fileLevelInput, self::fix($fileLevelInput));
        self::assertSame($namespaceInput, self::fix($namespaceInput));
    }

    #[Test()]
    public function testLeavesMatchArmsToNativeFixers(): void
    {
        $input = <<<'PHP'
            <?php

            function example(mixed $value): void
            {
                match ($value) {
                    1 => one(),

                    2 => two(),
                };
            }

            PHP;

        self::assertSame($input, self::fix($input));
    }

    #[DataProvider('provideSeparatesDifferentVisualStatementGroupsCases')]
    #[Test()]
    public function testSeparatesDifferentVisualStatementGroups(string $left, string $right): void
    {
        self::assertFunctionBodyStatementSpacing($left, $right, true);
    }

    /**
     * @return iterable<string, array{0: string, 1: string}>
     */
    public static function provideSeparatesDifferentVisualStatementGroupsCases(): iterable
    {
        yield 'assignment then function call' => ['$value = 1;', 'cleanup();'];
        yield 'function call then this method call' => ['cleanup();', '$this->cleanup();'];
        yield 'this method call then object method call' => ['$this->cleanup();', '$service->cleanup();'];
        yield 'object method call then static call' => ['$service->cleanup();', 'Service::cleanup();'];
        yield 'static call then assignment' => ['Service::cleanup();', '$value = 1;'];
        yield 'assignment then mutation' => ['$value = 1;', '++$counter;'];
        yield 'mutation then terminal' => ['++$counter;', 'return;'];
        yield 'include then function call' => ['include $file;', 'cleanup();'];
        yield 'output then function call' => ['echo $value;', 'cleanup();'];
        yield 'control then function call' => ['if ($condition) {}', 'cleanup();'];
        yield 'declaration then function call' => ['function local(): void {}', 'cleanup();'];
        yield 'object creation then function call' => ['new First();', 'cleanup();'];
        yield 'variable declaration then assignment' => ['global $global;', '$value = 1;'];
        yield 'label then function call' => ['first:', 'cleanup();'];
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

    #[Test()]
    public function testTreatsAttributesAndDeclarationModifiersAsPartOfTheDeclarationHead(): void
    {
        self::assertFunctionBodyStatementSpacing('#[Attribute] final class First {}', 'readonly class Second {}', false);
    }

    #[Test()]
    public function testTreatsUnsetAsFunctionLikeSingleLineStatement(): void
    {
        $input = <<<'PHP'
            <?php

            function example(): void
            {
                $resource = $this->resource->current;
                unset($this->resource->current);

                unset($this->resource->previous);

                cleanup();
            }

            PHP;

        $expected = <<<'PHP'
            <?php

            function example(): void
            {
                $resource = $this->resource->current;

                unset($this->resource->current);
                unset($this->resource->previous);
                cleanup();
            }

            PHP;

        self::assertSame($expected, self::fix($input));
    }

    private static function assertFunctionBodyStatementSpacing(string $left, string $right, bool $blankLine): void
    {
        self::assertStatementSpacing(
            self::functionBodyCode($left . self::oppositeStatementSeparator($blankLine) . $right),
            self::functionBodyCode($left . self::statementSeparator($blankLine) . $right),
        );
    }

    private static function assertStatementSpacing(string $input, string $expected): void
    {
        self::assertSame($expected, self::fix($input));
    }

    /**
     * @param array{separate_single_line_groups?: bool, separate_comment_led_statements?: bool} $configuration
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

    private static function functionBodyCode(string $body): string
    {
        return "<?php\n\n" . 'function example(mixed $value, mixed $other, mixed $items, mixed $object, mixed $service, mixed $condition, string $file, string $code, string $class): void' . "\n{\n" . self::indent($body) . "\n}\n";
    }

    private static function indent(string $code): string
    {
        $lines = explode("\n", $code);

        foreach ($lines as $index => $line) {
            if ($line !== '') {
                $lines[$index] = '    ' . $line;
            }
        }

        return implode("\n", $lines);
    }

    private static function oppositeStatementSeparator(bool $blankLine): string
    {
        return $blankLine ? "\n" : "\n\n";
    }

    private static function statementSeparator(bool $blankLine): string
    {
        return $blankLine ? "\n\n" : "\n";
    }
}
