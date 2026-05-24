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

namespace TomasChochola\Tooling\PhpCsFixer\Fixer\SiblingStatementSpacing;

/**
 * @no-named-arguments
 */
final readonly class Statement
{
    public function __construct(
        public int $visualStart,
        public int $coreStart,
        public int $coreEnd,
        public int $visualEnd,
        public string $group,
        public bool $multiline,
        public bool $coreMultiline,
        public bool $blockLike,
        public bool $hasLeadingComment,
        public bool $hasTrailingComment,
    ) {
    }
}
