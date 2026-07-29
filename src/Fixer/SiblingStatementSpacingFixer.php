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

namespace TomasChochola\Tooling\PhpCsFixer\Fixer;

use Override;
use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\Fixer\WhitespacesAwareFixerInterface;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolver;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixer\WhitespacesFixerConfig;
use SplFileInfo;
use TomasChochola\Tooling\PhpCsFixer\Fixer\SiblingStatementSpacing\Statement;

use function array_push;
use function array_values;
use function assert;
use function count;
use function in_array;
use function is_string;
use function preg_replace;
use function str_contains;
use function usort;

use const T_ABSTRACT;
use const T_AMPERSAND_FOLLOWED_BY_VAR_OR_VARARG;
use const T_AMPERSAND_NOT_FOLLOWED_BY_VAR_OR_VARARG;
use const T_AND_EQUAL;
use const T_ARRAY;
use const T_ATTRIBUTE;
use const T_BREAK;
use const T_CASE;
use const T_CATCH;
use const T_CLASS;
use const T_CLONE;
use const T_CLOSE_TAG;
use const T_COALESCE_EQUAL;
use const T_CONCAT_EQUAL;
use const T_CONST;
use const T_CONTINUE;
use const T_DEC;
use const T_DECLARE;
use const T_DEFAULT;
use const T_DIV_EQUAL;
use const T_DO;
use const T_DOUBLE_COLON;
use const T_ECHO;
use const T_ELSE;
use const T_ELSEIF;
use const T_EMPTY;
use const T_ENUM;
use const T_EVAL;
use const T_EXIT;
use const T_FINAL;
use const T_FINALLY;
use const T_FN;
use const T_FOR;
use const T_FOREACH;
use const T_FUNCTION;
use const T_GLOBAL;
use const T_GOTO;
use const T_IF;
use const T_INC;
use const T_INCLUDE;
use const T_INCLUDE_ONCE;
use const T_INTERFACE;
use const T_ISSET;
use const T_LIST;
use const T_MATCH;
use const T_MINUS_EQUAL;
use const T_MOD_EQUAL;
use const T_MUL_EQUAL;
use const T_NAMESPACE;
use const T_NEW;
use const T_OPEN_TAG;
use const T_OR_EQUAL;
use const T_PLUS_EQUAL;
use const T_POW_EQUAL;
use const T_PRINT;
use const T_READONLY;
use const T_REQUIRE;
use const T_REQUIRE_ONCE;
use const T_RETURN;
use const T_SL_EQUAL;
use const T_SR_EQUAL;
use const T_STATIC;
use const T_STRING;
use const T_SWITCH;
use const T_THROW;
use const T_TRAIT;
use const T_TRY;
use const T_UNSET;
use const T_VARIABLE;
use const T_WHILE;
use const T_WHITESPACE;
use const T_XOR_EQUAL;
use const T_YIELD;
use const T_YIELD_FROM;

/**
 * @phpstan-type _InputConfiguration array{
 *     separate_multiline_siblings?: bool,
 *     single_line_policy?: 'preserve'|'compact_same_group'|'compact_all',
 *     insert_blank_line_between_single_line_groups?: bool,
 *     leading_comment_policy?: 'ignore'|'attach_to_next_statement',
 *     trailing_comment_policy?: 'ignore'|'preserve_boundary',
 *     process_case_bodies?: bool,
 * }
 * @phpstan-type _ComputedConfiguration array{
 *     separate_multiline_siblings: bool,
 *     single_line_policy: 'preserve'|'compact_same_group'|'compact_all',
 *     insert_blank_line_between_single_line_groups: bool,
 *     leading_comment_policy: 'ignore'|'attach_to_next_statement',
 *     trailing_comment_policy: 'ignore'|'preserve_boundary',
 *     process_case_bodies: bool,
 * }
 *
 * @implements ConfigurableFixerInterface<_InputConfiguration, _ComputedConfiguration>
 *
 * @no-named-arguments
 */
final class SiblingStatementSpacingFixer implements ConfigurableFixerInterface, WhitespacesAwareFixerInterface
{
    public const NAME = 'TomasChochola/sibling_statement_spacing';

    /**
     * @var list<array{0: int}|string>
     */
    private const ASSIGNMENT_TOKENS = [
        '=',
        [T_AND_EQUAL],
        [T_COALESCE_EQUAL],
        [T_CONCAT_EQUAL],
        [T_DIV_EQUAL],
        [T_MINUS_EQUAL],
        [T_MOD_EQUAL],
        [T_MUL_EQUAL],
        [T_OR_EQUAL],
        [T_PLUS_EQUAL],
        [T_POW_EQUAL],
        [T_SL_EQUAL],
        [T_SR_EQUAL],
        [T_XOR_EQUAL],
    ];

    /**
     * @var list<int>
     */
    private const CONTROL_TOKENS = [
        T_DECLARE,
        T_DO,
        T_FOR,
        T_FOREACH,
        T_IF,
        T_SWITCH,
        T_TRY,
        T_WHILE,
    ];

    /**
     * @var list<int>
     */
    private const DECLARATION_MODIFIER_TOKENS = [
        T_ABSTRACT,
        T_FINAL,
        T_READONLY,
    ];

    /**
     * @var list<int>
     */
    private const DECLARATION_TOKENS = [
        T_CLASS,
        T_CONST,
        T_FUNCTION,
        T_INTERFACE,
        T_TRAIT,
        T_ENUM,
    ];

    private const GROUP_ASSIGNMENT = 'assignment';

    private const GROUP_CLONE_EXPRESSION = 'clone_expression';

    private const GROUP_CLOSURE = 'closure';

    private const GROUP_CONTROL = 'control';

    private const GROUP_DECLARATION = 'declaration';

    private const GROUP_FLOW_EXIT = 'flow_exit';

    private const GROUP_FLOW_GOTO = 'flow_goto';

    private const GROUP_FLOW_LOOP = 'flow_loop';

    private const GROUP_FLOW_RETURN = 'flow_return';

    private const GROUP_FLOW_THROW = 'flow_throw';

    private const GROUP_FLOW_YIELD = 'flow_yield';

    private const GROUP_FUNCTION_CALL = 'function_call';

    private const GROUP_INCLUDE = 'include';

    private const GROUP_LABEL = 'label';

    private const GROUP_LANGUAGE_CONSTRUCT = 'language_construct';

    private const GROUP_MATCH_EXPRESSION = 'match_expression';

    private const GROUP_MUTATION = 'mutation';

    private const GROUP_OBJECT_CREATION = 'object_creation';

    private const GROUP_OBJECT_METHOD_CALL = 'object_method_call';

    private const GROUP_OTHER_EXPRESSION = 'other_expression';

    private const GROUP_OUTPUT = 'output';

    private const GROUP_PREDICATE_CONSTRUCT = 'predicate_construct';

    private const GROUP_STATIC_CALL = 'static_call';

    private const GROUP_THIS_METHOD_CALL = 'this_method_call';

    private const GROUP_VARIABLE_DECLARATION = 'variable_declaration';

    /**
     * @var list<int>
     */
    private const INCLUDE_TOKENS = [
        T_INCLUDE,
        T_INCLUDE_ONCE,
        T_REQUIRE,
        T_REQUIRE_ONCE,
    ];

    /**
     * @var list<int>
     */
    private const OUTPUT_TOKENS = [
        T_ECHO,
        T_PRINT,
    ];

    /**
     * @var _ComputedConfiguration
     */
    private array $configuration;

    private FixerConfigurationResolverInterface | null $configurationDefinition = null;

    private WhitespacesFixerConfig $whitespacesConfig;

    public function __construct()
    {
        $this->whitespacesConfig = new WhitespacesFixerConfig('    ', "\n");

        $this->configure([]);
    }

    /**
     * @param _InputConfiguration $configuration
     */
    #[Override()]
    public function configure(array $configuration): void
    {
        /**
         * @var _ComputedConfiguration $computedConfiguration
         */
        $computedConfiguration = $this->getConfigurationDefinition()->resolve($configuration);
        $this->configuration = $computedConfiguration;
    }

    #[Override()]
    public function fix(SplFileInfo $file, Tokens $tokens): void
    {
        if (count($tokens) <= 0 || !$this->isCandidate($tokens) || !$this->supports($file)) {
            return;
        }

        $this->applyFix($tokens);
    }

    #[Override()]
    public function getConfigurationDefinition(): FixerConfigurationResolverInterface
    {
        if ($this->configurationDefinition === null) {
            $this->configurationDefinition = self::createConfigurationDefinition();
        }

        return $this->configurationDefinition;
    }

    #[Override()]
    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            'Normalizes blank lines between direct sibling statements: multiline or block-like statements are separated, leading comments are attached to the following statement, and single-line statements can be compacted by visual group.',
            [
                new CodeSample(
                    <<<'PHP'
                        <?php

                        function example(): void
                        {
                            imported_call();

                            global_call();
                            $this->boot();
                            $service->run(
                                $input,
                            );
                            $result = true;
                            // Explain the next statement.
                            return;
                        }

                        PHP,
                ),
                new CodeSample(
                    <<<'PHP'
                        <?php

                        function example(): void
                        {
                            imported_call();

                            global_call();

                            $this->boot();
                        }

                        PHP,
                    ['single_line_policy' => 'preserve'],
                ),
            ],
        );
    }

    #[Override()]
    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * Must run after `no_extra_blank_lines` and `blank_line_before_statement`, so this fixer can be the final
     * executable-sibling spacing authority without replacing the more specific built-in rules.
     */
    #[Override()]
    public function getPriority(): int
    {
        return -30;
    }

    #[Override()]
    public function isCandidate(Tokens $tokens): bool
    {
        return true;
    }

    #[Override()]
    public function isRisky(): bool
    {
        return false;
    }

    #[Override()]
    public function setWhitespacesConfig(WhitespacesFixerConfig $config): void
    {
        $this->whitespacesConfig = $config;
    }

    #[Override()]
    public function supports(SplFileInfo $file): bool
    {
        return true;
    }

    /**
     * @param list<int> $tokenKinds
     */
    private static function blockOwnerContains(Tokens $tokens, int $blockStart, array $tokenKinds): bool
    {
        for ($index = $blockStart - 1; $index >= 0; --$index) {
            $token = $tokens[$index];

            if ($token->equalsAny([';', '{', '}']) || $token->isGivenKind([T_OPEN_TAG, T_CLOSE_TAG])) {
                return false;
            }

            if ($token->isGivenKind($tokenKinds)) {
                return true;
            }
        }

        return false;
    }

    private static function canCurlyBlockEndStatement(Token $statementStart): bool
    {
        return $statementStart->isGivenKind([
            T_CATCH,
            T_CLASS,
            T_DECLARE,
            T_DO,
            T_ELSE,
            T_ELSEIF,
            T_ENUM,
            T_FINALLY,
            T_FOR,
            T_FOREACH,
            T_FUNCTION,
            T_IF,
            T_INTERFACE,
            T_NAMESPACE,
            T_SWITCH,
            T_TRAIT,
            T_TRY,
            T_WHILE,
        ]);
    }

    private static function classifyStatement(Tokens $tokens, int $start, int $end): string
    {
        $head = self::findStatementHead($tokens, $start, $end);
        $first = $tokens[$head];

        if ($first->isGivenKind(T_RETURN)) {
            return self::GROUP_FLOW_RETURN;
        }

        if ($first->isGivenKind(T_THROW)) {
            return self::GROUP_FLOW_THROW;
        }

        if ($first->isGivenKind([T_YIELD, T_YIELD_FROM])) {
            return self::GROUP_FLOW_YIELD;
        }

        if ($first->isGivenKind([T_BREAK, T_CONTINUE])) {
            return self::GROUP_FLOW_LOOP;
        }

        if ($first->isGivenKind(T_GOTO)) {
            return self::GROUP_FLOW_GOTO;
        }

        if ($first->isGivenKind(T_EXIT)) {
            return self::GROUP_FLOW_EXIT;
        }

        if ($first->isGivenKind(self::CONTROL_TOKENS)) {
            return self::GROUP_CONTROL;
        }

        if ($first->isGivenKind(self::INCLUDE_TOKENS)) {
            return self::GROUP_INCLUDE;
        }

        if ($first->isGivenKind(self::OUTPUT_TOKENS)) {
            return self::GROUP_OUTPUT;
        }

        if (self::isLabelStatement($tokens, $head, $end)) {
            return self::GROUP_LABEL;
        }

        if ($first->isGivenKind(T_FN) || ($first->isGivenKind(T_FUNCTION) && !self::isNamedFunctionDeclaration($tokens, $head))) {
            return self::GROUP_CLOSURE;
        }

        if ($first->isGivenKind(self::DECLARATION_TOKENS)) {
            return self::GROUP_DECLARATION;
        }

        if (self::hasTopLevelAssignment($tokens, $start, $end)) {
            return self::GROUP_ASSIGNMENT;
        }

        if ($first->isGivenKind(T_UNSET) || self::hasTopLevelMutation($tokens, $start, $end)) {
            return self::GROUP_MUTATION;
        }

        if ($first->isGivenKind([T_EMPTY, T_ISSET])) {
            return self::GROUP_PREDICATE_CONSTRUCT;
        }

        if ($first->isGivenKind(T_EVAL)) {
            return self::GROUP_LANGUAGE_CONSTRUCT;
        }

        if ($first->isGivenKind(T_MATCH)) {
            return self::GROUP_MATCH_EXPRESSION;
        }

        if ($first->isGivenKind(T_ARRAY) || $first->isGivenKind(T_LIST)) {
            return self::GROUP_OTHER_EXPRESSION;
        }

        if ($first->isGivenKind(T_NEW)) {
            return self::GROUP_OBJECT_CREATION;
        }

        if ($first->isGivenKind(T_CLONE)) {
            return self::GROUP_CLONE_EXPRESSION;
        }

        $dispatchToken = self::findFirstTopLevelToken(
            $tokens,
            $start,
            $end,
            static fn(Token $token): bool => $token->equals('(') || $token->equals([T_DOUBLE_COLON]) || $token->isObjectOperator(),
        );

        if ($dispatchToken !== null) {
            if ($tokens[$dispatchToken]->equals([T_DOUBLE_COLON])) {
                return self::GROUP_STATIC_CALL;
            }

            if ($tokens[$dispatchToken]->isObjectOperator()) {
                return self::isThisTarget($tokens, $start) ? self::GROUP_THIS_METHOD_CALL : self::GROUP_OBJECT_METHOD_CALL;
            }

            return self::GROUP_FUNCTION_CALL;
        }

        if ($first->isGivenKind([T_GLOBAL, T_STATIC])) {
            return self::GROUP_VARIABLE_DECLARATION;
        }

        return 'other:' . self::statementSignature($first);
    }

    /**
     * @return list<array{start: int, end: int}>
     */
    private static function collectSwitchCaseRanges(Tokens $tokens, int $blockStart, int $blockEnd): array
    {
        $ranges = [];

        for ($index = $blockStart + 1; $index < $blockEnd; ++$index) {
            if (self::isBlockStart($tokens[$index])) {
                $index = self::findBlockEnd($tokens, $index);

                continue;
            }

            if (!$tokens[$index]->isGivenKind([T_CASE, T_DEFAULT])) {
                continue;
            }

            $labelEnd = self::findSwitchLabelEnd($tokens, $index, $blockEnd);
            $bodyStart = $labelEnd + 1;
            $bodyEnd = self::findSwitchCaseBodyEnd($tokens, $bodyStart, $blockEnd);

            if ($bodyStart <= $bodyEnd) {
                $ranges[] = [
                    'start' => $bodyStart,
                    'end' => $bodyEnd,
                ];
            }

            $index = $bodyEnd;
        }

        return $ranges;
    }

    private static function containsNewline(string $content): bool
    {
        return str_contains($content, "\n") || str_contains($content, "\r");
    }

    private static function createConfigurationDefinition(): FixerConfigurationResolverInterface
    {
        return new FixerConfigurationResolver([
            (new FixerOptionBuilder(
                'separate_multiline_siblings',
                'Whether sibling statements should be separated when at least one of them is multiline or block-like.',
            ))
                ->setAllowedTypes(['bool'])
                ->setDefault(true)
                ->getOption(),
            (new FixerOptionBuilder(
                'single_line_policy',
                'How blank lines between single-line sibling statements should be normalized.',
            ))
                ->setAllowedTypes(['string'])
                ->setAllowedValues(['preserve', 'compact_same_group', 'compact_all'])
                ->setDefault('compact_same_group')
                ->getOption(),
            (new FixerOptionBuilder(
                'insert_blank_line_between_single_line_groups',
                'Whether different single-line statement groups should be separated by a blank line.',
            ))
                ->setAllowedTypes(['bool'])
                ->setDefault(false)
                ->getOption(),
            (new FixerOptionBuilder(
                'leading_comment_policy',
                'How standalone leading comments between sibling statements should be attached to the next statement.',
            ))
                ->setAllowedTypes(['string'])
                ->setAllowedValues(['ignore', 'attach_to_next_statement'])
                ->setDefault('attach_to_next_statement')
                ->getOption(),
            (new FixerOptionBuilder(
                'trailing_comment_policy',
                'How single-line sibling boundaries after trailing inline comments should be handled.',
            ))
                ->setAllowedTypes(['string'])
                ->setAllowedValues(['ignore', 'preserve_boundary'])
                ->setDefault('preserve_boundary')
                ->getOption(),
            (new FixerOptionBuilder(
                'process_case_bodies',
                'Whether sibling statement spacing should be normalized inside switch case/default bodies.',
            ))
                ->setAllowedTypes(['bool'])
                ->setDefault(true)
                ->getOption(),
        ]);
    }

    private static function detectIndent(Tokens $tokens, int $start, int $end): string
    {
        for ($index = $end; $index >= $start; --$index) {
            if (!$tokens[$index]->isWhitespace()) {
                continue;
            }

            $content = $tokens[$index]->getContent();

            if (self::containsNewline($content)) {
                $indent = preg_replace('/^.*\R/s', '', $content);

                assert(is_string($indent));

                return $indent;
            }
        }

        return '';
    }

    private static function findBlockEnd(Tokens $tokens, int $blockStart): int
    {
        $blockType = Tokens::detectBlockType($tokens[$blockStart]);

        assert($blockType !== null);
        assert($blockType['isStart'] === true);

        return $tokens->findBlockEnd($blockType['type'], $blockStart);
    }

    /**
     * @param callable(Token): bool $predicate
     */
    private static function findFirstTopLevelToken(Tokens $tokens, int $start, int $end, callable $predicate): int | null
    {
        for ($index = $start; $index <= $end; ++$index) {
            if ($predicate($tokens[$index])) {
                return $index;
            }

            if (self::isBlockStart($tokens[$index])) {
                $index = self::findBlockEnd($tokens, $index);

                continue;
            }
        }

        return null;
    }

    private static function findLastCommentBetween(Tokens $tokens, int $start, int $end): int | null
    {
        for ($index = $end; $index >= $start; --$index) {
            if ($tokens[$index]->isComment()) {
                return $index;
            }
        }

        return null;
    }

    private static function findNextMeaningful(Tokens $tokens, int $start, int $end): int | null
    {
        for ($index = $start; $index <= $end; ++$index) {
            if (!$tokens[$index]->isWhitespace() && !$tokens[$index]->isComment()) {
                return $index;
            }
        }

        return null;
    }

    private static function findNextNonWhitespace(Tokens $tokens, int $start, int $end): int | null
    {
        for ($index = $start; $index <= $end; ++$index) {
            if (!$tokens[$index]->isWhitespace()) {
                return $index;
            }
        }

        return null;
    }

    private static function findStatementHead(Tokens $tokens, int $start, int $limit): int
    {
        $head = $start;

        while ($head <= $limit) {
            if ($tokens[$head]->isGivenKind(T_ATTRIBUTE) && self::isBlockStart($tokens[$head])) {
                $next = $tokens->getNextMeaningfulToken(self::findBlockEnd($tokens, $head));

                if ($next === null || $next > $limit) {
                    return $start;
                }

                $head = $next;

                continue;
            }

            if ($tokens[$head]->isGivenKind(self::DECLARATION_MODIFIER_TOKENS)) {
                $next = $tokens->getNextMeaningfulToken($head);

                if ($next === null || $next > $limit) {
                    return $head;
                }

                $head = $next;

                continue;
            }

            return $head;
        }

        return $start;
    }

    private static function findSwitchCaseBodyEnd(Tokens $tokens, int $bodyStart, int $blockEnd): int
    {
        for ($index = $bodyStart; $index < $blockEnd; ++$index) {
            if (self::isBlockStart($tokens[$index])) {
                $index = self::findBlockEnd($tokens, $index);

                continue;
            }

            if ($tokens[$index]->isGivenKind([T_CASE, T_DEFAULT])) {
                return $index - 1;
            }
        }

        return $blockEnd - 1;
    }

    private static function findSwitchLabelEnd(Tokens $tokens, int $labelStart, int $blockEnd): int
    {
        for ($index = $labelStart + 1; $index < $blockEnd; ++$index) {
            if (self::isBlockStart($tokens[$index])) {
                $index = self::findBlockEnd($tokens, $index);

                continue;
            }

            if ($tokens[$index]->equalsAny([':', ';'])) {
                return $index;
            }
        }

        return $labelStart;
    }

    private static function findVisualEnd(Tokens $tokens, int $coreEnd, int $limit): int
    {
        $lineEnd = $coreEnd;

        for ($index = $coreEnd + 1; $index <= $limit; ++$index) {
            if ($tokens[$index]->isWhitespace()) {
                if (self::containsNewline($tokens[$index]->getContent())) {
                    break;
                }

                continue;
            }

            if (!$tokens[$index]->isComment()) {
                break;
            }

            $lineEnd = $index;

            if (self::containsNewline($tokens[$index]->getContent())) {
                break;
            }
        }

        return $lineEnd;
    }

    private static function hasCommentBetween(Tokens $tokens, int $start, int $end): bool
    {
        for ($index = $start; $index <= $end; ++$index) {
            if ($tokens[$index]->isComment()) {
                return true;
            }
        }

        return false;
    }

    private static function hasTopLevelAssignment(Tokens $tokens, int $start, int $end): bool
    {
        return self::findFirstTopLevelToken(
            $tokens,
            $start,
            $end,
            static fn(Token $token): bool => $token->equalsAny(self::ASSIGNMENT_TOKENS),
        ) !== null;
    }

    private static function hasTopLevelMutation(Tokens $tokens, int $start, int $end): bool
    {
        if ($tokens[$start]->isGivenKind([T_INC, T_DEC])) {
            return true;
        }

        return self::findFirstTopLevelToken(
            $tokens,
            $start,
            $end,
            static fn(Token $token): bool => $token->isGivenKind([T_INC, T_DEC]),
        ) !== null;
    }

    private static function isAnonymousFunctionStart(Tokens $tokens, int $functionIndex): bool
    {
        return !self::isNamedFunctionDeclaration($tokens, $functionIndex);
    }

    private static function isBlockLikeStatement(Tokens $tokens, int $start, int $end, string $group): bool
    {
        if (!in_array($group, [self::GROUP_CONTROL, self::GROUP_DECLARATION], true)) {
            return false;
        }

        return self::findFirstTopLevelToken(
            $tokens,
            $start,
            $end,
            static fn(Token $token): bool => $token->equals('{'),
        ) !== null;
    }

    private static function isBlockStart(Token $token): bool
    {
        $blockType = Tokens::detectBlockType($token);

        return $blockType !== null && $blockType['isStart'] === true;
    }

    private static function isClassLikeBlock(Tokens $tokens, int $blockStart): bool
    {
        return self::blockOwnerContains($tokens, $blockStart, [T_CLASS, T_INTERFACE, T_TRAIT, T_ENUM]);
    }

    private static function isExecutableBlock(Tokens $tokens, int $blockStart): bool
    {
        return !self::isClassLikeBlock($tokens, $blockStart)
            && !self::isNamespaceBlock($tokens, $blockStart)
            && !self::isMatchBlock($tokens, $blockStart);
    }

    private static function isLabelStatement(Tokens $tokens, int $start, int $limit): bool
    {
        if (!$tokens[$start]->isGivenKind(T_STRING)) {
            return false;
        }

        $next = $tokens->getNextMeaningfulToken($start);

        return $next !== null && $next <= $limit && $tokens[$next]->equals(':');
    }

    private static function isMatchBlock(Tokens $tokens, int $blockStart): bool
    {
        return self::blockOwnerContains($tokens, $blockStart, [T_MATCH]);
    }

    private static function isNamedFunctionDeclaration(Tokens $tokens, int $functionIndex): bool
    {
        $next = $tokens->getNextMeaningfulToken($functionIndex);

        if ($next === null) {
            return false;
        }

        if ($tokens[$next]->equalsAny(['&', [T_AMPERSAND_FOLLOWED_BY_VAR_OR_VARARG], [T_AMPERSAND_NOT_FOLLOWED_BY_VAR_OR_VARARG]])) {
            $next = $tokens->getNextMeaningfulToken($next);
        }

        return $next !== null && $tokens[$next]->isGivenKind(T_STRING);
    }

    private static function isNamespaceBlock(Tokens $tokens, int $blockStart): bool
    {
        return self::blockOwnerContains($tokens, $blockStart, [T_NAMESPACE]);
    }

    private static function isRangeMultiline(Tokens $tokens, int $start, int $end): bool
    {
        for ($index = $start; $index <= $end; ++$index) {
            if (self::containsNewline($tokens[$index]->getContent())) {
                return true;
            }
        }

        return false;
    }

    private static function isSwitchBlock(Tokens $tokens, int $blockStart): bool
    {
        return self::blockOwnerContains($tokens, $blockStart, [T_SWITCH]);
    }

    private static function isThisTarget(Tokens $tokens, int $statementStart): bool
    {
        return $tokens[$statementStart]->equals([T_VARIABLE, '$this']);
    }

    private static function statementSignature(Token $token): string
    {
        if ($token->isArray()) {
            return (string) $token->getId();
        }

        return $token->getContent();
    }

    private function applyFix(Tokens $tokens): void
    {
        $replacements = $this->collectSpacingReplacements($tokens);

        if ($replacements === []) {
            return;
        }

        usort(
            $replacements,
            static fn(array $left, array $right): int => $right['right_start'] <=> $left['right_start'],
        );

        foreach ($replacements as $replacement) {
            $this->normalizeSpacing(
                $tokens,
                $replacement['left_end'],
                $replacement['right_start'],
                $replacement['blank_line'],
            );
        }
    }

    /**
     * @return list<array{left_end: int, right_start: int, blank_line: bool}>
     */
    private function collectSpacingReplacements(Tokens $tokens): array
    {
        $replacements = [];
        $tokenCount = count($tokens);

        for ($index = 0; $index < $tokenCount; ++$index) {
            if (!$tokens[$index]->equals('{')) {
                continue;
            }

            $blockType = Tokens::detectBlockType($tokens[$index]);

            if ($blockType === null || $blockType['isStart'] === false) {
                continue;
            }

            $blockEnd = $tokens->findBlockEnd($blockType['type'], $index);

            if (self::isSwitchBlock($tokens, $index)) {
                if ($this->configuration['process_case_bodies']) {
                    foreach (self::collectSwitchCaseRanges($tokens, $index, $blockEnd) as $range) {
                        array_push($replacements, ...$this->collectStatementListReplacements($tokens, $range['start'], $range['end']));
                    }
                }

                continue;
            }

            if (!self::isExecutableBlock($tokens, $index)) {
                continue;
            }

            array_push($replacements, ...$this->collectStatementListReplacements($tokens, $index + 1, $blockEnd - 1));
        }

        return $replacements;
    }

    /**
     * @return list<array{left_end: int, right_start: int, blank_line: bool}>
     */
    private function collectStatementListReplacements(Tokens $tokens, int $start, int $end): array
    {
        $statements = $this->collectStatements($tokens, $start, $end);
        $replacements = [];
        $previous = null;

        foreach ($statements as $statement) {
            if ($this->configuration['leading_comment_policy'] === 'attach_to_next_statement' && $statement->hasLeadingComment) {
                $lastLeadingComment = self::findLastCommentBetween($tokens, $statement->visualStart, $statement->coreStart - 1);

                if ($lastLeadingComment !== null) {
                    $replacements[$statement->coreStart] = [
                        'left_end' => $lastLeadingComment,
                        'right_start' => $statement->coreStart,
                        'blank_line' => false,
                    ];
                }
            }

            if ($previous === null) {
                $previous = $statement;

                continue;
            }

            $blankLine = $this->determineBlankLineBetween($previous, $statement);

            if ($blankLine !== null) {
                $replacements[$statement->visualStart] = [
                    'left_end' => $previous->visualEnd,
                    'right_start' => $statement->visualStart,
                    'blank_line' => $blankLine,
                ];
            }

            $previous = $statement;
        }

        return array_values($replacements);
    }

    /**
     * @return list<Statement>
     */
    private function collectStatements(Tokens $tokens, int $start, int $end): array
    {
        $statements = [];
        $cursor = $start;

        while ($cursor <= $end) {
            $visualStart = self::findNextNonWhitespace($tokens, $cursor, $end);

            if ($visualStart === null) {
                break;
            }

            $coreStart = self::findNextMeaningful($tokens, $visualStart, $end);

            if ($coreStart === null) {
                break;
            }

            $coreEnd = $this->findStatementEnd($tokens, $coreStart, $end);
            $visualEnd = self::findVisualEnd($tokens, $coreEnd, $end);
            $group = self::classifyStatement($tokens, $coreStart, $coreEnd);

            $statements[] = new Statement(
                $visualStart,
                $coreStart,
                $coreEnd,
                $visualEnd,
                $group,
                self::isRangeMultiline($tokens, $visualStart, $visualEnd),
                self::isRangeMultiline($tokens, $coreStart, $visualEnd),
                self::isBlockLikeStatement($tokens, $coreStart, $coreEnd, $group),
                self::hasCommentBetween($tokens, $visualStart, $coreStart - 1),
                $visualEnd !== $coreEnd,
            );

            $cursor = $visualEnd + 1;
        }

        return $statements;
    }

    private function determineBlankLineBetween(Statement $left, Statement $right): bool | null
    {
        if (
            $this->configuration['separate_multiline_siblings']
            && ($left->coreMultiline || $right->coreMultiline || $left->blockLike || $right->blockLike)
        ) {
            return true;
        }

        if ($this->configuration['leading_comment_policy'] === 'attach_to_next_statement' && $right->hasLeadingComment) {
            return true;
        }

        if ($right->hasLeadingComment) {
            return null;
        }

        if ($this->configuration['trailing_comment_policy'] === 'preserve_boundary' && $left->hasTrailingComment) {
            return null;
        }

        if ($this->configuration['single_line_policy'] === 'compact_all') {
            return false;
        }

        if ($this->configuration['single_line_policy'] === 'compact_same_group' && $left->group === $right->group) {
            return false;
        }

        if ($this->configuration['insert_blank_line_between_single_line_groups'] && $left->group !== $right->group) {
            return true;
        }

        return null;
    }

    private function findControlStatementEnd(Tokens $tokens, int $statementStart, int $blockEnd, int $limit): int
    {
        $statementHead = self::findStatementHead($tokens, $statementStart, $limit);
        $first = $tokens[$statementHead];

        if ($first->isGivenKind([T_IF, T_ELSEIF])) {
            $next = $tokens->getNextMeaningfulToken($blockEnd);

            if ($next !== null && $next <= $limit && $tokens[$next]->isGivenKind([T_ELSEIF, T_ELSE])) {
                return $this->findStatementEnd($tokens, $next, $limit);
            }
        }

        if ($first->isGivenKind([T_TRY, T_CATCH])) {
            $next = $tokens->getNextMeaningfulToken($blockEnd);

            if ($next !== null && $next <= $limit && $tokens[$next]->isGivenKind([T_CATCH, T_FINALLY])) {
                return $this->findStatementEnd($tokens, $next, $limit);
            }
        }

        if ($first->isGivenKind(T_DO)) {
            $next = $tokens->getNextMeaningfulToken($blockEnd);

            if ($next !== null && $next <= $limit && $tokens[$next]->isGivenKind(T_WHILE)) {
                return $this->findStatementEnd($tokens, $next, $limit);
            }
        }

        return $blockEnd;
    }

    private function findStatementEnd(Tokens $tokens, int $statementStart, int $limit): int
    {
        $statementHead = self::findStatementHead($tokens, $statementStart, $limit);
        $first = $tokens[$statementHead];

        if (self::isLabelStatement($tokens, $statementHead, $limit)) {
            $next = $tokens->getNextMeaningfulToken($statementHead);

            assert($next !== null);

            return $next;
        }

        if ($first->isGivenKind(T_ELSE)) {
            $next = $tokens->getNextMeaningfulToken($statementHead);

            if ($next !== null && $next <= $limit && $tokens[$next]->isGivenKind(T_IF)) {
                return $this->findStatementEnd($tokens, $next, $limit);
            }
        }

        for ($index = $statementStart; $index <= $limit; ++$index) {
            if (self::isBlockStart($tokens[$index])) {
                $blockEnd = self::findBlockEnd($tokens, $index);

                if (
                    $tokens[$index]->equals('{')
                    && self::canCurlyBlockEndStatement($first)
                    && (!$first->isGivenKind(T_FUNCTION) || !self::isAnonymousFunctionStart($tokens, $statementHead))
                ) {
                    return $this->findControlStatementEnd($tokens, $statementStart, $blockEnd, $limit);
                }

                $index = $blockEnd;

                continue;
            }

            if ($tokens[$index]->equals(';')) {
                return $index;
            }
        }

        return $limit;
    }

    private function normalizeSpacing(Tokens $tokens, int $leftEnd, int $rightStart, bool $blankLine): void
    {
        $desired = $this->whitespacesConfig->getLineEnding();

        if ($blankLine) {
            $desired .= $this->whitespacesConfig->getLineEnding();
        }

        $desired .= self::detectIndent($tokens, $leftEnd + 1, $rightStart - 1);

        if ($rightStart <= $leftEnd + 1) {
            $tokens->insertAt($rightStart, new Token([T_WHITESPACE, $desired]));

            return;
        }

        $current = '';

        for ($index = $leftEnd + 1; $index < $rightStart; ++$index) {
            if (!$tokens[$index]->isWhitespace()) {
                return;
            }

            $current .= $tokens[$index]->getContent();
        }

        if ($current === $desired) {
            return;
        }

        $tokens[$leftEnd + 1] = new Token([T_WHITESPACE, $desired]);

        for ($index = $leftEnd + 2; $index < $rightStart; ++$index) {
            $tokens->clearAt($index);
        }
    }
}
