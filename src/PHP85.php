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

use TomasChochola\Tooling\PhpCsFixer\Fixer\SiblingStatementSpacingFixer;

/**
 * @no-named-arguments
 */
readonly class PHP85
{
    /**
     * @return array<string, array<string, mixed>|bool>
     */
    public static function strictRules(): array
    {
        return [
            'align_multiline_comment' => [
                'comment_type' => 'all_multiline',
            ],
            'array_indentation' => true,
            'array_push' => true,
            'array_syntax' => [
                'syntax' => 'short',
            ],
            'assign_null_coalescing_to_coalesce_equal' => true,
            'attribute_block_no_spaces' => true,
            'attribute_empty_parentheses' => [
                'use_parentheses' => true,
            ],
            'backtick_to_shell_exec' => true,
            'binary_operator_spaces' => true,
            'blank_line_after_namespace' => true,
            'blank_line_after_opening_tag' => true,
            'blank_line_before_statement' => [
                'statements' => [
                    'break',
                    'case',
                    'continue',
                    'declare',
                    'default',
                    'do',
                    'exit',
                    'for',
                    'foreach',
                    'goto',
                    'if',
                    'include',
                    'include_once',
                    'phpdoc',
                    'require',
                    'require_once',
                    'return',
                    'switch',
                    'throw',
                    'try',
                    'while',
                    'yield',
                    'yield_from',
                ],
            ],
            'blank_line_between_import_groups' => true,
            'blank_lines_before_namespace' => true,
            SiblingStatementSpacingFixer::NAME => true,
            'braces_position' => [
                'allow_single_line_anonymous_functions' => false,
                'allow_single_line_empty_anonymous_classes' => false,
                'anonymous_classes_opening_brace' => 'same_line',
                'anonymous_functions_opening_brace' => 'same_line',
                'classes_opening_brace' => 'next_line_unless_newline_at_signature_end',
                'control_structures_opening_brace' => 'same_line',
                'functions_opening_brace' => 'next_line_unless_newline_at_signature_end',
            ],
            'cast_spaces' => [
                'space' => 'single',
            ],
            'class_attributes_separation' => [
                'elements' => [
                    'case' => 'one',
                    'const' => 'one',
                    'method' => 'one',
                    'property' => 'one',
                    'trait_import' => 'none',
                ],
            ],
            'class_definition' => [
                'inline_constructor_arguments' => false,
                'multi_line_extends_each_single_line' => true,
                'single_item_single_line' => false,
                'single_line' => false,
                'space_before_parenthesis' => true,
            ],
            'class_reference_name_casing' => true,
            'clean_namespace' => true,
            'combine_consecutive_issets' => true,
            'combine_consecutive_unsets' => true,
            'combine_nested_dirname' => true,
            'comment_to_phpdoc' => [
                'ignored_tags' => [
                    'todo',
                    'TODO',
                    'fixme',
                    'FIXME',
                ],
            ],
            'compact_nullable_type_declaration' => true,
            'concat_space' => [
                'spacing' => 'one',
            ],
            'constant_case' => [
                'case' => 'lower',
            ],
            'control_structure_braces' => true,
            'control_structure_continuation_position' => [
                'position' => 'same_line',
            ],
            'date_time_create_from_format_call' => true,
            'date_time_immutable' => true,
            'declare_equal_normalize' => [
                'space' => 'none',
            ],
            'declare_parentheses' => true,
            'declare_strict_types' => [
                'strategy' => 'enforce',
            ],
            'dir_constant' => true,
            'echo_tag_syntax' => [
                'format' => 'long',
                'long_function' => 'echo',
                'shorten_simple_statements_only' => false,
            ],
            'elseif' => true,
            'empty_loop_body' => [
                'style' => 'braces',
            ],
            'empty_loop_condition' => [
                'style' => 'while',
            ],
            'encoding' => true,
            'ereg_to_preg' => true,
            'error_suppression' => [
                'mute_deprecation_error' => false,
                'noise_remaining_usages' => true,
            ],
            'explicit_indirect_variable' => true,
            'explicit_string_variable' => true,
            'fopen_flag_order' => true,
            'fopen_flags' => [
                'b_mode' => true,
            ],
            'full_opening_tag' => true,
            'fully_qualified_strict_types' => [
                'import_symbols' => true,
                'leading_backslash_in_global_namespace' => true,
            ],
            'function_declaration' => [
                'closure_fn_spacing' => 'none',
                'closure_function_spacing' => 'one',
                'trailing_comma_single_line' => false,
            ],
            'function_to_constant' => true,
            'general_phpdoc_annotation_remove' => [
                'annotations' => [
                    'author',
                ],
                'case_sensitive' => false,
            ],
            'general_phpdoc_tag_rename' => [
                'replacements' => [
                    'inheritDocs' => 'inheritDoc',
                ],
            ],
            'get_class_to_class_keyword' => true,
            'global_namespace_import' => [
                'import_classes' => true,
                'import_constants' => true,
                'import_functions' => true,
            ],
            'heredoc_closing_marker' => [
                'closing_marker' => 'EOF',
                'explicit_heredoc_style' => true,
            ],
            'heredoc_indentation' => true,
            'heredoc_to_nowdoc' => true,
            'implode_call' => true,
            'include' => true,
            'increment_style' => true,
            'indentation_type' => true,
            'integer_literal_case' => true,
            'is_null' => true,
            'lambda_not_used_import' => true,
            'line_ending' => true,
            'linebreak_after_opening_tag' => true,
            'list_syntax' => [
                'syntax' => 'short',
            ],
            'logical_operators' => true,
            'long_to_shorthand_operator' => true,
            'lowercase_cast' => true,
            'lowercase_keywords' => true,
            'lowercase_static_reference' => true,
            'magic_constant_casing' => true,
            'magic_method_casing' => true,
            'mb_str_functions' => true,
            'method_argument_space' => [
                'after_heredoc' => true,
                'attribute_placement' => 'standalone',
                'keep_multiple_spaces_after_comma' => false,
                'on_multiline' => 'ensure_fully_multiline',
            ],
            'method_chaining_indentation' => true,
            'modern_serialization_methods' => true,
            'modernize_strpos' => [
                'modernize_stripos' => true,
            ],
            'modernize_types_casting' => true,
            'modifier_keywords' => [
                'elements' => [
                    'const',
                    'method',
                    'property',
                ],
            ],
            'multiline_comment_opening_closing' => true,
            'multiline_string_to_heredoc' => true,
            'multiline_whitespace_before_semicolons' => [
                'strategy' => 'no_multi_line',
            ],
            'native_constant_invocation' => [
                'fix_built_in' => true,
                'scope' => 'all',
                'strict' => true,
            ],
            'native_function_casing' => true,
            'native_function_invocation' => [
                'include' => [
                    '@all',
                ],
                'scope' => 'all',
                'strict' => true,
            ],
            'native_type_declaration_casing' => true,
            'new_expression_parentheses' => [
                'use_parentheses' => true,
            ],
            'new_with_parentheses' => [
                'anonymous_class' => true,
                'named_class' => true,
            ],
            'no_alias_functions' => [
                'sets' => [
                    '@all',
                ],
            ],
            'no_alias_language_construct_call' => true,
            'no_alternative_syntax' => [
                'fix_non_monolithic_code' => true,
            ],
            'no_binary_string' => true,
            'no_blank_lines_after_class_opening' => true,
            'no_blank_lines_after_phpdoc' => true,
            'no_break_comment' => true,
            'no_closing_tag' => true,
            'no_empty_comment' => true,
            'no_empty_phpdoc' => true,
            'no_empty_statement' => true,
            'no_extra_blank_lines' => [
                'tokens' => [
                    'extra',
                    'use',
                ],
            ],
            'no_homoglyph_names' => true,
            'no_leading_import_slash' => true,
            'no_leading_namespace_whitespace' => true,
            'no_mixed_echo_print' => [
                'use' => 'echo',
            ],
            'no_multiline_whitespace_around_double_arrow' => true,
            'no_multiple_statements_per_line' => true,
            'no_null_property_initialization' => true,
            'no_php4_constructor' => true,
            'no_redundant_readonly_property' => true,
            'no_short_bool_cast' => true,
            'no_singleline_whitespace_before_semicolons' => true,
            'no_space_around_double_colon' => true,
            'no_spaces_after_function_name' => true,
            'no_spaces_around_offset' => [
                'positions' => [
                    'inside',
                    'outside',
                ],
            ],
            'no_superfluous_elseif' => true,
            'no_superfluous_phpdoc_tags' => [
                'allow_hidden_params' => false,
                'allow_mixed' => false,
                'allow_unused_params' => false,
                'remove_inheritdoc' => true,
            ],
            'no_trailing_comma_in_singleline' => [
                'elements' => [
                    'arguments',
                    'array',
                    'array_destructuring',
                    'group_import',
                ],
            ],
            'no_trailing_whitespace' => true,
            'no_trailing_whitespace_in_comment' => true,
            'no_trailing_whitespace_in_string' => true,
            'no_unneeded_braces' => [
                'namespaces' => true,
            ],
            'no_unneeded_control_parentheses' => [
                'statements' => [
                    'break',
                    'clone',
                    'continue',
                    'echo_print',
                    'negative_instanceof',
                    'others',
                    'return',
                    'switch_case',
                    'yield',
                    'yield_from',
                ],
            ],
            'no_unneeded_final_method' => [
                'private_methods' => true,
            ],
            'no_unneeded_import_alias' => true,
            'no_unreachable_default_argument_value' => true,
            'no_unset_cast' => true,
            'no_unset_on_property' => true,
            'no_unused_imports' => true,
            'no_useless_concat_operator' => [
                'juggle_simple_strings' => true,
            ],
            'no_useless_else' => true,
            'no_useless_nullsafe_operator' => true,
            'no_useless_printf' => true,
            'no_useless_return' => true,
            'no_useless_sprintf' => true,
            'no_whitespace_before_comma_in_array' => [
                'after_heredoc' => true,
            ],
            'no_whitespace_in_blank_line' => true,
            'no_whitespace_in_empty_array' => true,
            'non_printable_character' => [
                'use_escape_sequences_in_strings' => true,
            ],
            'normalize_index_brace' => true,
            'nullable_type_declaration' => [
                'syntax' => 'union',
            ],
            'nullable_type_declaration_for_default_null_value' => true,
            'numeric_literal_separator' => [
                'override_existing' => true,
                'strategy' => 'use_separator',
            ],
            'object_operator_without_whitespace' => true,
            'octal_notation' => true,
            'operator_linebreak' => [
                'only_booleans' => false,
                'position' => 'beginning',
            ],
            'ordered_attributes' => [
                'sort_algorithm' => 'alpha',
            ],
            'ordered_class_elements' => [
                'case_sensitive' => true,
                'order' => [
                    'use_trait',
                    'case',
                    'constant_public',
                    'constant_protected',
                    'constant_private',
                    'property_public_abstract',
                    'property_protected_abstract',
                    'property_public_static',
                    'property_protected_static',
                    'property_private_static',
                    'property_public_readonly',
                    'property_public',
                    'property_protected_readonly',
                    'property_protected',
                    'property_private_readonly',
                    'property_private',
                    'construct',
                    'destruct',
                    'magic',
                    'phpunit',
                    'method_public_abstract_static',
                    'method_public_abstract',
                    'method_protected_abstract_static',
                    'method_protected_abstract',
                    'method_private_abstract_static',
                    'method_private_abstract',
                    'method_public_static',
                    'method_public',
                    'method_protected_static',
                    'method_protected',
                    'method_private_static',
                    'method_private',
                ],
                'sort_algorithm' => 'alpha',
            ],
            'ordered_imports' => [
                'case_sensitive' => true,
                'imports_order' => [
                    'class',
                    'function',
                    'const',
                ],
                'sort_algorithm' => 'alpha',
            ],
            'ordered_interfaces' => [
                'case_sensitive' => true,
                'direction' => 'ascend',
                'order' => 'alpha',
            ],
            'ordered_traits' => [
                'case_sensitive' => true,
            ],
            'ordered_types' => [
                'case_sensitive' => true,
                'null_adjustment' => 'always_last',
                'sort_algorithm' => 'alpha',
            ],
            'php_unit_assert_new_names' => true,
            'php_unit_attributes' => [
                'keep_annotations' => false,
            ],
            'php_unit_construct' => true,
            'php_unit_data_provider_method_order' => [
                'placement' => 'after',
            ],
            'php_unit_data_provider_name' => [
                'prefix' => 'provide',
                'suffix' => 'Cases',
            ],
            'php_unit_data_provider_return_type' => true,
            'php_unit_data_provider_static' => [
                'force' => true,
            ],
            'php_unit_dedicate_assert' => [
                'target' => 'newest',
            ],
            'php_unit_dedicate_assert_internal_type' => [
                'target' => 'newest',
            ],
            'php_unit_expectation' => [
                'target' => 'newest',
            ],
            'php_unit_fqcn_annotation' => true,
            'php_unit_internal_class' => [
                'types' => [
                    'abstract',
                    'final',
                    'normal',
                ],
            ],
            'php_unit_method_casing' => [
                'case' => 'camel_case',
            ],
            'php_unit_mock' => [
                'target' => 'newest',
            ],
            'php_unit_mock_short_will_return' => true,
            'php_unit_namespaced' => [
                'target' => 'newest',
            ],
            'php_unit_no_expectation_annotation' => [
                'target' => 'newest',
                'use_class_const' => true,
            ],
            'php_unit_set_up_tear_down_visibility' => true,
            'php_unit_size_class' => [
                'group' => 'small',
            ],
            'php_unit_strict' => true,
            'php_unit_test_annotation' => false,
            'php_unit_test_case_static_method_calls' => [
                'call_type' => 'self',
                'target' => 'newest',
            ],
            'php_unit_test_class_requires_covers' => true,
            'phpdoc_align' => [
                'align' => 'left',
                'tags' => [
                    'param',
                    'property',
                    'property-read',
                    'property-write',
                    'phpstan-param',
                    'phpstan-property',
                    'phpstan-property-read',
                    'phpstan-property-write',
                    'phpstan-assert',
                    'phpstan-assert-if-true',
                    'phpstan-assert-if-false',
                    'psalm-param',
                    'psalm-param-out',
                    'psalm-property',
                    'psalm-property-read',
                    'psalm-property-write',
                    'psalm-assert',
                    'psalm-assert-if-true',
                    'psalm-assert-if-false',
                    'method',
                    'phpstan-method',
                    'psalm-method',
                    'return',
                    'throws',
                    'type',
                    'var',
                ],
            ],
            'phpdoc_annotation_without_dot' => true,
            'phpdoc_array_type' => true,
            'phpdoc_indent' => true,
            'phpdoc_inline_tag_normalizer' => true,
            'phpdoc_line_span' => [
                'case' => 'multi',
                'class' => 'multi',
                'const' => 'multi',
                'function' => 'multi',
                'method' => 'multi',
                'other' => 'multi',
                'property' => 'multi',
                'trait_import' => null,
            ],
            'phpdoc_list_type' => true,
            'phpdoc_no_access' => true,
            'phpdoc_no_alias_tag' => [
                'replacements' => [
                    'const' => 'var',
                    'type' => 'var',
                    'link' => 'see',
                ],
            ],
            'phpdoc_no_duplicate_types' => true,
            'phpdoc_no_empty_return' => true,
            'phpdoc_no_package' => true,
            'phpdoc_no_useless_inheritdoc' => true,
            'phpdoc_order' => [
                'order' => [
                    'template',
                    'param',
                    'return',
                    'throws',
                    'phpstan-assert',
                    'psalm-assert',
                ],
            ],
            'phpdoc_order_by_value' => [
                'annotations' => [
                    'author',
                    'covers',
                    'coversNothing',
                    'dataProvider',
                    'depends',
                    'group',
                    'internal',
                    'method',
                    'mixin',
                    'property',
                    'property-read',
                    'property-write',
                    'requires',
                    'throws',
                    'uses',
                ],
            ],
            'phpdoc_param_order' => true,
            'phpdoc_readonly_class_comment_to_keyword' => true,
            'phpdoc_return_self_reference' => true,
            'phpdoc_scalar' => [
                'types' => [
                    'boolean',
                    'callback',
                    'double',
                    'integer',
                    'never-return',
                    'never-returns',
                    'no-return',
                    'real',
                    'str',
                ],
            ],
            'phpdoc_separation' => [
                'skip_unlisted_annotations' => false,
            ],
            'phpdoc_single_line_var_spacing' => true,
            'phpdoc_summary' => true,
            'phpdoc_tag_casing' => true,
            'phpdoc_tag_no_named_arguments' => [
                'description' => '',
                'fix_attribute' => true,
                'fix_internal' => true,
            ],
            'phpdoc_tag_type' => [
                'tags' => [
                    'api' => 'annotation',
                    'author' => 'annotation',
                    'copyright' => 'annotation',
                    'deprecated' => 'annotation',
                    'example' => 'annotation',
                    'global' => 'annotation',
                    'inheritDoc' => 'inline',
                    'internal' => 'annotation',
                    'license' => 'annotation',
                    'method' => 'annotation',
                    'package' => 'annotation',
                    'param' => 'annotation',
                    'property' => 'annotation',
                    'return' => 'annotation',
                    'see' => 'annotation',
                    'since' => 'annotation',
                    'throws' => 'annotation',
                    'todo' => 'annotation',
                    'uses' => 'annotation',
                    'var' => 'annotation',
                    'version' => 'annotation',
                ],
            ],
            'phpdoc_to_comment' => [
                'allow_before_return_statement' => true,
            ],
            'phpdoc_trim' => true,
            'phpdoc_trim_consecutive_blank_line_separation' => true,
            'phpdoc_types' => true,
            'phpdoc_types_order' => [
                'case_sensitive' => true,
                'null_adjustment' => 'always_last',
                'sort_algorithm' => 'alpha',
            ],
            'phpdoc_var_annotation_correct_order' => true,
            'phpdoc_var_without_name' => true,
            'pow_to_exponentiation' => true,
            'protected_to_private' => true,
            'psr_autoloading' => true,
            'random_api_migration' => [
                'replacements' => [
                    'mt_rand' => 'random_int',
                    'rand' => 'random_int',
                ],
            ],
            'regular_callable_call' => true,
            'return_assignment' => [
                'skip_named_var_tags' => true,
            ],
            'return_to_yield_from' => true,
            'return_type_declaration' => [
                'space_before' => 'none',
            ],
            'self_accessor' => true,
            'self_static_accessor' => true,
            'semicolon_after_instruction' => true,
            'set_type_to_cast' => true,
            'short_scalar_cast' => true,
            'simple_to_complex_string_variable' => true,
            'simplified_null_return' => true,
            'single_blank_line_at_eof' => true,
            'single_class_element_per_statement' => [
                'elements' => [
                    'const',
                    'property',
                ],
            ],
            'single_import_per_statement' => [
                'group_to_single_imports' => true,
            ],
            'single_line_after_imports' => true,
            'single_line_comment_spacing' => true,
            'single_line_comment_style' => [
                'comment_types' => [
                    'asterisk',
                    'hash',
                ],
            ],
            'single_quote' => [
                'strings_containing_single_quote_chars' => true,
            ],
            'single_space_around_construct' => true,
            'single_trait_insert_per_statement' => true,
            'space_after_semicolon' => [
                'remove_in_empty_for_expressions' => true,
            ],
            'spaces_inside_parentheses' => [
                'space' => 'none',
            ],
            'standardize_increment' => true,
            'standardize_not_equals' => true,
            'statement_indentation' => [
                'stick_comment_to_next_continuous_control_statement' => true,
            ],
            'static_lambda' => true,
            'static_private_method' => true,
            'strict_comparison' => true,
            'strict_param' => true,
            'string_implicit_backslashes' => [
                'double_quoted' => 'escape',
                'heredoc' => 'escape',
                'single_quoted' => 'unescape',
            ],
            'string_length_to_empty' => true,
            'string_line_ending' => true,
            'stringable_for_to_string' => true,
            'switch_case_semicolon_to_colon' => true,
            'switch_case_space' => true,
            'switch_continue_to_break' => true,
            'ternary_operator_spaces' => true,
            'ternary_to_null_coalescing' => true,
            'trailing_comma_in_multiline' => [
                'after_heredoc' => true,
                'elements' => [
                    'arguments',
                    'array_destructuring',
                    'arrays',
                    'match',
                    'parameters',
                ],
            ],
            'trim_array_spaces' => true,
            'type_declaration_spaces' => [
                'elements' => [
                    'constant',
                    'function',
                    'property',
                ],
            ],
            'types_spaces' => [
                'space' => 'single',
                'space_multiple_catch' => 'single',
            ],
            'unary_operator_spaces' => [
                'only_dec_inc' => false,
            ],
            'use_arrow_functions' => true,
            'void_return' => [
                'fix_lambda' => true,
            ],
            'whitespace_after_comma_in_array' => [
                'ensure_single_space' => true,
            ],
            'yield_from_array_to_yields' => true,
            'yoda_style' => [
                'always_move_variable' => false,
                'equal' => false,
                'identical' => false,
                'less_and_greater' => false,
            ],
        ];
    }

    /**
     * @return array<string, array<string, mixed>|bool>
     */
    public static function tomasChocholaFileHeaderRules(): array
    {
        return [
            'header_comment' => [
                'comment_type' => 'PHPDoc',
                'header' => <<<'EOF'
                    @author Tomáš Chochola <tomaschochola@tomaschochola.cz>
                    @copyright © 2026 Tomáš Chochola <tomaschochola@tomaschochola.cz>

                    @license CC-BY-ND-4.0

                    @see {@link https://creativecommons.org/licenses/by-nd/4.0/} License
                    @see {@link https://github.com/tomaschochola} GitHub Profile
                    @see {@link https://github.com/sponsors/tomaschochola} GitHub Sponsors
                    EOF,
                'location' => 'after_open',
            ],
        ];
    }
}
