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
    public function testCanCompactAllSingleLineStatements(): void
    {
        $input = <<<'PHP'
            <?php

            function example(): void
            {
                $value = 1;

                cleanup();
            }

            PHP;

        $expected = <<<'PHP'
            <?php

            function example(): void
            {
                $value = 1;
                cleanup();
            }

            PHP;

        self::assertSame($expected, self::fix($input, ['single_line_policy' => 'compact_all']));
    }

    #[Test()]
    public function testCanDisableMultilineSiblingSeparationWhileKeepingSameGroupCompaction(): void
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
            }

            PHP;

        self::assertSame($expected, self::fix($input, ['separate_multiline_siblings' => false]));
    }

    #[Test()]
    public function testCanDisableProcessingSwitchCaseBodies(): void
    {
        $input = <<<'PHP'
            <?php

            function example(string $type): void
            {
                switch ($type) {
                    case 'a':
                        first();

                        second();
                        break;
                }
            }

            PHP;

        self::assertSame($input, self::fix($input, ['process_case_bodies' => false]));
    }

    #[Test()]
    public function testCanIgnoreTrailingInlineCommentBoundary(): void
    {
        $input = <<<'PHP'
            <?php

            function example(): void
            {
                first(); // explain first

                second();
            }

            PHP;

        $expected = <<<'PHP'
            <?php

            function example(): void
            {
                first(); // explain first
                second();
            }

            PHP;

        self::assertSame($expected, self::fix($input, ['trailing_comment_policy' => 'ignore']));
    }

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

        self::assertSame($input, self::fix($input, ['leading_comment_policy' => 'ignore']));
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

        self::assertSame($expected, self::fix($input, ['single_line_policy' => 'preserve']));
    }

    #[DataProvider('provideCanSeparateDifferentVisualStatementGroupsCases')]
    #[Test()]
    public function testCanSeparateDifferentVisualStatementGroups(string $left, string $right): void
    {
        self::assertFunctionBodyStatementSpacing($left, $right, true, ['insert_blank_line_between_single_line_groups' => true]);
    }

    /**
     * @return iterable<string, array{0: string, 1: string}>
     */
    public static function provideCanSeparateDifferentVisualStatementGroupsCases(): iterable
    {
        yield 'assignment then function call' => ['$value = 1;', 'cleanup();'];
        yield 'function call then this method call' => ['cleanup();', '$this->cleanup();'];
        yield 'this method call then object method call' => ['$this->cleanup();', '$service->cleanup();'];
        yield 'object method call then static call' => ['$service->cleanup();', 'Service::cleanup();'];
        yield 'static call then assignment' => ['Service::cleanup();', '$value = 1;'];
        yield 'assignment then mutation' => ['$value = 1;', '++$counter;'];
        yield 'mutation then flow return' => ['++$counter;', 'return;'];
        yield 'include then function call' => ['include $file;', 'cleanup();'];
        yield 'output then function call' => ['echo $value;', 'cleanup();'];
        yield 'control then function call' => ['if ($condition) {}', 'cleanup();'];
        yield 'declaration then function call' => ['function local(): void {}', 'cleanup();'];
        yield 'object creation then function call' => ['new First();', 'cleanup();'];
        yield 'variable declaration then assignment' => ['global $global;', '$value = 1;'];
        yield 'label then function call' => ['first:', 'cleanup();'];
    }

    #[Test()]
    public function testCanSeparateSingleLineStatementsByStatementShape(): void
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

        self::assertSame($expected, self::fix($input, ['insert_blank_line_between_single_line_groups' => true]));
    }

    #[Test()]
    public function testCompactAllDoesNotOverrideManagedLeadingCommentBoundary(): void
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

        $expected = <<<'PHP'
            <?php

            function example(): void
            {
                first();

                // Explain second.
                second();
            }

            PHP;

        self::assertSame($expected, self::fix($input, ['single_line_policy' => 'compact_all']));
    }

    #[Test()]
    public function testCompactAllDoesNotOverrideMultilineBoundaries(): void
    {
        $input = <<<'PHP'
            <?php

            function example(): void
            {
                first(
                    1,
                );
                second();
                third(
                    3,
                );
            }

            PHP;

        $expected = <<<'PHP'
            <?php

            function example(): void
            {
                first(
                    1,
                );

                second();

                third(
                    3,
                );
            }

            PHP;

        self::assertSame($expected, self::fix($input, ['single_line_policy' => 'compact_all']));
    }

    #[DataProvider('provideCompactsSpecialSameSingleLineGroupsCases')]
    #[Test()]
    public function testCompactsSpecialSameSingleLineGroups(string $left, string $right): void
    {
        self::assertFunctionBodyStatementSpacing($left, $right, false);
    }

    /**
     * @return iterable<string, array{0: string, 1: string}>
     */
    public static function provideCompactsSpecialSameSingleLineGroupsCases(): iterable
    {
        yield 'exit and die flow exit' => ['exit;', 'die();'];
        yield 'throw statements' => ['throw $value;', 'throw $other;'];
        yield 'yield statements' => ['yield $value;', 'yield from $items;'];
        yield 'goto statements' => ['goto first;', 'goto second;'];
        yield 'clone expressions' => ['clone $object;', 'clone $other;'];
        yield 'other array expressions' => ['array();', 'array(1);'];
    }

    #[Test()]
    public function testDefaultPreservesBlankLinesBetweenDifferentSingleLineGroups(): void
    {
        $input = <<<'PHP'
            <?php

            function example(): void
            {
                $value = 1;

                cleanup();
            }

            PHP;

        self::assertSame($input, self::fix($input));
    }

    #[Test()]
    public function testDoesNotTreatForSemicolonsAsStatementBoundaries(): void
    {
        $input = <<<'PHP'
            <?php

            function example(): void
            {
                for ($i = 0; $i < 10; ++$i) {
                    first();

                    second();
                }
                after();
            }

            PHP;

        $expected = <<<'PHP'
            <?php

            function example(): void
            {
                for ($i = 0; $i < 10; ++$i) {
                    first();
                    second();
                }

                after();
            }

            PHP;

        self::assertSame($expected, self::fix($input));
    }

    #[Test()]
    public function testDoesNotTreatTernaryOrNamedArgumentColonAsLabel(): void
    {
        $input = <<<'PHP'
            <?php

            function example(): void
            {
                $value = $condition ? first() : second();

                $other = create(name: 'value');
            }

            PHP;

        $expected = <<<'PHP'
            <?php

            function example(): void
            {
                $value = $condition ? first() : second();
                $other = create(name: 'value');
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
        yield 'predicate constructs' => ['isset($value);', 'empty($value);'];
        yield 'mutations' => ['unset($value);', '++$counter;'];
        yield 'this method calls' => ['$this->first();', '$this?->second();'];
        yield 'object method calls' => ['$service->first();', '$other?->second();'];
        yield 'static calls' => ['self::first();', '$class::second();'];
        yield 'assignments' => ['$first = 1;', '$second += 2;'];
        yield 'destructuring assignments' => ['[$first] = $items;', 'list($second) = $items;'];
        yield 'increments' => ['++$counter;', '$counter--;'];
        yield 'return statements' => ['return $value;', 'return;'];
        yield 'loop flow statements' => ['break;', 'continue;'];
        yield 'include statements' => ['include $file;', 'require_once $file;'];
        yield 'output statements' => ['echo $value;', 'print $value;'];
        yield 'object creation expressions' => ['new First();', 'new Second();'];
        yield 'variable declarations' => ['global $global;', 'static $local;'];
        yield 'closures' => ['function (): void {};', 'fn (): null => null;'];
        yield 'labels' => ['first:', 'second:'];
    }

    #[Test()]
    public function testKeepsSingleLineClosureAndAnonymousClassAssignmentsCompact(): void
    {
        $input = <<<'PHP'
            <?php

            function example(): void
            {
                $callback = function (): void {};

                $object = new class {};
            }

            PHP;

        $expected = <<<'PHP'
            <?php

            function example(): void
            {
                $callback = function (): void {};
                $object = new class {};
            }

            PHP;

        self::assertSame($expected, self::fix($input));
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
    public function testLeadingCommentIgnorePreservesCommentBoundaryEvenForSameGroups(): void
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

        self::assertSame($input, self::fix($input, ['leading_comment_policy' => 'ignore']));
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

    #[Test()]
    public function testNestedFunctionAndAnonymousClassMethodBodiesAreProcessed(): void
    {
        $input = <<<'PHP'
            <?php

            function example(): void
            {
                function local(): void
                {
                    first();

                    second();
                }

                $object = new class {
                    public function run(): void
                    {
                        third();

                        fourth();
                    }
                };
            }

            PHP;

        $expected = <<<'PHP'
            <?php

            function example(): void
            {
                function local(): void
                {
                    first();
                    second();
                }

                $object = new class {
                    public function run(): void
                    {
                        third();
                        fourth();
                    }
                };
            }

            PHP;

        self::assertSame($expected, self::fix($input));
    }

    #[Test()]
    public function testPreservesBoundaryAfterTrailingInlineComment(): void
    {
        $input = <<<'PHP'
            <?php

            function example(): void
            {
                first(); // explain first

                second();
            }

            PHP;

        self::assertSame($input, self::fix($input));
    }

    #[DataProvider('providePreservesDifferentSingleLineGroupsByDefaultCases')]
    #[Test()]
    public function testPreservesDifferentSingleLineGroupsByDefault(string $left, string $right): void
    {
        $blankLineInput = self::functionBodyCode($left . "\n\n" . $right);
        $noBlankLineInput = self::functionBodyCode($left . "\n" . $right);

        self::assertSame($blankLineInput, self::fix($blankLineInput));
        self::assertSame($noBlankLineInput, self::fix($noBlankLineInput));
    }

    /**
     * @return iterable<string, array{0: string, 1: string}>
     */
    public static function providePreservesDifferentSingleLineGroupsByDefaultCases(): iterable
    {
        yield 'assignment then function call' => ['$value = 1;', 'cleanup();'];
        yield 'function call then this method call' => ['cleanup();', '$this->cleanup();'];
        yield 'this method call then object method call' => ['$this->cleanup();', '$service->cleanup();'];
        yield 'static call then assignment' => ['Service::cleanup();', '$value = 1;'];
        yield 'mutation then return' => ['++$counter;', 'return;'];
        yield 'output then include' => ['echo $value;', 'include $file;'];
        yield 'predicate then language construct' => ['isset($value);', 'eval($code);'];
        yield 'object creation then clone' => ['new First();', 'clone $object;'];
    }

    #[Test()]
    public function testProcessesSwitchCaseBodiesWithoutChangingCaseSeparation(): void
    {
        $input = <<<'PHP'
            <?php

            function example(string $type): void
            {
                switch ($type) {
                    case 'a':
                        first();

                        second(
                            1,
                        );
                        break;

                    case 'b':
                        third();

                        fourth();
                        break;
                }
            }

            PHP;

        $expected = <<<'PHP'
            <?php

            function example(string $type): void
            {
                switch ($type) {
                    case 'a':
                        first();

                        second(
                            1,
                        );

                        break;

                    case 'b':
                        third();
                        fourth();
                        break;
                }
            }

            PHP;

        self::assertSame($expected, self::fix($input));
    }

    #[DataProvider('provideSeparatesBlockLikeStatementKindsCases')]
    #[Test()]
    public function testSeparatesBlockLikeStatementKinds(string $left, string $right): void
    {
        self::assertFunctionBodyStatementSpacing($left, $right, true);
    }

    /**
     * @return iterable<string, array{0: string, 1: string}>
     */
    public static function provideSeparatesBlockLikeStatementKindsCases(): iterable
    {
        yield 'if' => ['if ($condition) {}', 'after();'];
        yield 'foreach' => ['foreach ($items as $item) {}', 'after();'];
        yield 'for' => ['for ($i = 0; $i < 1; ++$i) {}', 'after();'];
        yield 'while' => ['while ($condition) {}', 'after();'];
        yield 'do while' => ['do {} while ($condition);', 'after();'];
        yield 'switch' => ['switch ($value) {}', 'after();'];
        yield 'try catch' => ['try {} catch (\Throwable $e) {}', 'after();'];
        yield 'declare block' => ['declare(ticks=1) {}', 'after();'];
        yield 'local function declaration' => ['function local(): void {}', 'after();'];
        yield 'local class declaration' => ['class Local {}', 'after();'];
    }

    #[DataProvider('provideSeparatesMultilineStatementKindsCases')]
    #[Test()]
    public function testSeparatesMultilineStatementKinds(string $left, string $right): void
    {
        self::assertFunctionBodyStatementSpacing($left, $right, true);
    }

    /**
     * @return iterable<string, array{0: string, 1: string}>
     */
    public static function provideSeparatesMultilineStatementKindsCases(): iterable
    {
        yield 'multiline old array' => [
            <<<'PHP'
                $value = array(
                    'a' => 1,
                );
                PHP,
            'after();',
        ];

        yield 'multiline named call' => [
            <<<'PHP'
                $value = create(
                    alpha: 1,
                    beta: 2,
                );
                PHP,
            'after();',
        ];

        yield 'multiline nullsafe method call' => [
            <<<'PHP'
                $value = $service?->create(
                    $input,
                );
                PHP,
            'after();',
        ];

        yield 'multiline static call' => [
            <<<'PHP'
                $value = Service::create(
                    $input,
                );
                PHP,
            'after();',
        ];

        yield 'multiline ternary' => [
            <<<'PHP'
                $value = $condition
                    ? first()
                    : second();
                PHP,
            'after();',
        ];

        yield 'multiline match assignment' => [
            <<<'PHP'
                $value = match ($input) {
                    'a' => first(),
                    default => second(),
                };
                PHP,
            'after();',
        ];

        yield 'multiline arrow function rhs' => [
            <<<'PHP'
                $callback = fn (): mixed => create(
                    $input,
                );
                PHP,
            'after();',
        ];

        yield 'multiline closure assignment' => [
            <<<'PHP'
                $callback = function (): void {
                    first();
                };
                PHP,
            'after();',
        ];

        yield 'multiline anonymous class assignment' => [
            <<<'PHP'
                $object = new class {
                    public function run(): void {}
                };
                PHP,
            'after();',
        ];

        yield 'heredoc assignment' => [
            <<<'PHP'
                $sql = <<<SQL
                SELECT *
                FROM users
                SQL;
                PHP,
            'after();',
        ];
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
        self::assertFunctionBodyStatementSpacing('#[Attribute] final class First {}', 'readonly class Second {}', true);
    }

    #[Test()]
    public function testTreatsUnsetAsMutationWithoutForcingDifferentSingleLineGroups(): void
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

    /**
     * @param array{
     *     separate_multiline_siblings?: bool,
     *     single_line_policy?: 'compact_all'|'compact_same_group'|'preserve',
     *     insert_blank_line_between_single_line_groups?: bool,
     *     leading_comment_policy?: 'attach_to_next_statement'|'ignore',
     *     trailing_comment_policy?: 'ignore'|'preserve_boundary',
     *     process_case_bodies?: bool,
     * } $configuration
     */
    private static function assertFunctionBodyStatementSpacing(string $left, string $right, bool $blankLine, array $configuration = []): void
    {
        self::assertStatementSpacing(
            self::functionBodyCode($left . self::oppositeStatementSeparator($blankLine) . $right),
            self::functionBodyCode($left . self::statementSeparator($blankLine) . $right),
            $configuration,
        );
    }

    /**
     * @param array{
     *     separate_multiline_siblings?: bool,
     *     single_line_policy?: 'compact_all'|'compact_same_group'|'preserve',
     *     insert_blank_line_between_single_line_groups?: bool,
     *     leading_comment_policy?: 'attach_to_next_statement'|'ignore',
     *     trailing_comment_policy?: 'ignore'|'preserve_boundary',
     *     process_case_bodies?: bool,
     * } $configuration
     */
    private static function assertStatementSpacing(string $input, string $expected, array $configuration = []): void
    {
        self::assertSame($expected, self::fix($input, $configuration));
    }

    /**
     * @param array{
     *     separate_multiline_siblings?: bool,
     *     single_line_policy?: 'compact_all'|'compact_same_group'|'preserve',
     *     insert_blank_line_between_single_line_groups?: bool,
     *     leading_comment_policy?: 'attach_to_next_statement'|'ignore',
     *     trailing_comment_policy?: 'ignore'|'preserve_boundary',
     *     process_case_bodies?: bool,
     * } $configuration
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
