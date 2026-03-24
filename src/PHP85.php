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

namespace TomasChochola\Tooling\PhpCsFixerConfig;

/**
 * @no-named-arguments
 *
 * align_multiline_comment
 * array_syntax
 * attribute_empty_parentheses
 * binary_operator_spaces
 * blank_line_before_statement
 * blank_lines_before_namespace
 * braces_position
 * cast_spaces
 * class_attributes_separation
 * class_definition
 * comment_to_phpdoc
 * concat_space
 * constant_case
 * control_structure_continuation_position
 * declare_equal_normalize
 * declare_strict_types
 * doctrine_annotation_array_assignment
 * doctrine_annotation_braces
 * doctrine_annotation_indentation
 * doctrine_annotation_spaces
 * echo_tag_syntax
 * empty_loop_body
 * empty_loop_condition
 * error_suppression
 * final_internal_class
 * fopen_flags
 * fully_qualified_strict_types
 * function_declaration
 * function_to_constant
 * general_attribute_remove
 * general_phpdoc_annotation_remove
 * general_phpdoc_tag_rename
 * global_namespace_import
 * group_import
 * header_comment
 * heredoc_closing_marker
 * heredoc_indentation
 * increment_style
 * list_syntax
 * method_argument_space
 * modernize_strpos
 * modifier_keywords
 * multiline_whitespace_before_semicolons
 * native_constant_invocation
 * native_function_invocation
 * new_expression_parentheses
 * new_with_parentheses
 * no_alias_functions
 * no_alternative_syntax
 * no_break_comment
 * no_extra_blank_lines
 * no_mixed_echo_print
 * no_spaces_around_offset
 * no_superfluous_phpdoc_tags
 * no_trailing_comma_in_singleline
 * no_unneeded_braces
 * no_unneeded_control_parentheses
 * no_unneeded_final_method
 * no_whitespace_before_comma_in_array
 * non_printable_character
 * nullable_type_declaration
 * nullable_type_declaration_for_default_null_value
 * numeric_literal_separator
 * operator_linebreak
 * ordered_attributes
 * ordered_class_elements
 * ordered_imports
 * ordered_interfaces
 * ordered_traits
 * ordered_types
 * php_unit_attributes
 * php_unit_construct
 * php_unit_data_provider_method_order
 * php_unit_data_provider_name
 * php_unit_data_provider_static
 * php_unit_dedicate_assert
 * php_unit_dedicate_assert_internal_type
 * php_unit_expectation
 * php_unit_internal_class
 * php_unit_method_casing
 * php_unit_mock
 * php_unit_namespaced
 * php_unit_no_expectation_annotation
 * php_unit_size_class
 * php_unit_strict
 * php_unit_test_annotation
 * php_unit_test_case_static_method_calls
 * phpdoc_add_missing_param_annotation
 * phpdoc_align
 * phpdoc_inline_tag_normalizer
 * phpdoc_line_span
 * phpdoc_no_alias_tag
 * phpdoc_order
 * phpdoc_order_by_value
 * phpdoc_return_self_reference
 * phpdoc_scalar
 * phpdoc_separation
 * phpdoc_tag_casing
 * phpdoc_tag_no_named_arguments
 * phpdoc_tag_type
 * phpdoc_to_comment
 * phpdoc_types
 * phpdoc_types_order
 * psr_autoloading
 * random_api_migration
 * return_type_declaration
 * single_class_element_per_statement
 * single_import_per_statement
 * single_line_comment_style
 * single_quote
 * single_space_around_construct
 * space_after_semicolon
 * spaces_inside_parentheses
 * statement_indentation
 * string_implicit_backslashes
 * trailing_comma_in_multiline
 * type_declaration_spaces
 * types_spaces
 * unary_operator_spaces
 * void_return
 * whitespace_after_comma_in_array
 * yoda_style
 * no_useless_concat_operator
 */
readonly class PHP85
{
    /**
     * @return array<string, array<string, mixed>|bool>
     */
    public static function base(): array
    {
        return [
            'align_multiline_comment' => [
                'comment_type' => 'phpdocs_like',
            ],
            'array_indentation' => true,
            'array_push' => true,
            'array_syntax' => true,
            'assign_null_coalescing_to_coalesce_equal' => true,
            'attribute_empty_parentheses' => true,
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
            'braces_position' => true,
            'cast_spaces' => true,
            'class_attributes_separation' => [
                'elements' => [
                    'const' => 'one',
                    'method' => 'one',
                    'property' => 'one',
                    'trait_import' => 'none',
                    'case' => 'one',
                ],
            ],
            'class_definition' => [
                'single_line' => true,
                'space_before_parenthesis' => true,
            ],
            'class_reference_name_casing' => true,
            'clean_namespace' => true,
            'combine_consecutive_issets' => true,
            'combine_consecutive_unsets' => true,
            'combine_nested_dirname' => true,
            'comment_to_phpdoc' => true,
            'compact_nullable_type_declaration' => true,
            'concat_space' => [
                'spacing' => 'one',
            ],
            'constant_case' => true,
            'control_structure_braces' => true,
            'control_structure_continuation_position' => true,
            'date_time_create_from_format_call' => true,
            'date_time_immutable' => true,
            'declare_equal_normalize' => true,
            'declare_parentheses' => true,
            'declare_strict_types' => true,
            'dir_constant' => true,
            'doctrine_annotation_array_assignment' => false,
            'doctrine_annotation_braces' => false,
            'doctrine_annotation_indentation' => false,
            'doctrine_annotation_spaces' => false,
            'echo_tag_syntax' => [
                'shorten_simple_statements_only' => false,
            ],
            'elseif' => true,
            'empty_loop_body' => [
                'style' => 'braces',
            ],
            'empty_loop_condition' => true,
            'encoding' => true,
            'ereg_to_preg' => true,
            'error_suppression' => [
                'mute_deprecation_error' => false,
                'noise_remaining_usages' => true,
            ],
            'explicit_indirect_variable' => true,
            'explicit_string_variable' => true,
            'final_class' => false,
            'final_internal_class' => [
                'exclude' => [],
            ],
            'final_public_method_for_abstract_class' => true,
            'fopen_flag_order' => true,
            'fopen_flags' => [
                'b_mode' => false,
            ],
            'full_opening_tag' => true,
            'fully_qualified_strict_types' => [
                'import_symbols' => true,
            ],
            'function_declaration' => true,
            'function_to_constant' => true,
            'general_attribute_remove' => false,
            'general_phpdoc_annotation_remove' => [
                'annotations' => [
                    'throws',
                    'author',
                    'after',
                    'afterClass',
                    'backupGlobals',
                    'backupStaticAttributes',
                    'before',
                    'beforeClass',
                    'codeCoverageIgnore',
                    'codeCoverageIgnoreStart',
                    'codeCoverageIgnoreEnd',
                    'covers',
                    'coversDefaultClass',
                    'coversNothing',
                    'dataProvider',
                    'depends',
                    'doesNotPerformAssertions',
                    'group',
                    'large',
                    'medium',
                    'preserveGlobalState',
                    'requires',
                    'runTestsInSeparateProcesses',
                    'runInSeparateProcess',
                    'small',
                    'test',
                    'testdox',
                    'testWith',
                    'ticket',
                    'uses',
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
            'group_import' => false,
            'header_comment' => false,
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
            'list_syntax' => true,
            'logical_operators' => true,
            'long_to_shorthand_operator' => true,
            'lowercase_cast' => true,
            'lowercase_keywords' => true,
            'lowercase_static_reference' => true,
            'magic_constant_casing' => true,
            'magic_method_casing' => true,
            'mb_str_functions' => true,
            'method_argument_space' => [
                'on_multiline' => 'ensure_single_line',
            ],
            'method_chaining_indentation' => true,
            'modernize_strpos' => true,
            'modernize_types_casting' => true,
            'modifier_keywords' => true,
            'multiline_comment_opening_closing' => true,
            'multiline_string_to_heredoc' => true,
            'multiline_whitespace_before_semicolons' => true,
            'native_constant_invocation' => true,
            'native_function_casing' => true,
            'native_function_invocation' => [
                'include' => [
                    '@all',
                ],
            ],
            'native_type_declaration_casing' => true,
            'new_expression_parentheses' => [
                'use_parentheses' => true,
            ],
            'new_with_parentheses' => [
                'anonymous_class' => true,
            ],
            'no_alias_functions' => [
                'sets' => [
                    '@all',
                    '@exif',
                    '@ftp',
                    '@IMAP',
                    '@internal',
                    '@ldap',
                    '@mbreg',
                    '@mysqli',
                    '@oci',
                    '@odbc',
                    '@openssl',
                    '@pcntl',
                    '@pg',
                    '@posix',
                    '@snmp',
                    '@sodium',
                    '@time'
                ],
            ],
            'no_alias_language_construct_call' => true,
            'no_alternative_syntax' => true,
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
                    'attribute',
                    'break',
                    'case',
                    'comma',
                    'continue',
                    'curly_brace_block',
                    'default',
                    'extra',
                    'parenthesis_brace_block',
                    'return',
                    'square_brace_block',
                    'switch',
                    'throw',
                    'use',
                ],
            ],
            'no_homoglyph_names' => true,
            'no_leading_import_slash' => true,
            'no_leading_namespace_whitespace' => true,
            'no_mixed_echo_print' => true,
            'no_multiline_whitespace_around_double_arrow' => true,
            'no_multiple_statements_per_line' => true,
            'no_null_property_initialization' => true,
            'no_php4_constructor' => true,
            'no_short_bool_cast' => true,
            'no_singleline_whitespace_before_semicolons' => true,
            'no_space_around_double_colon' => true,
            'no_spaces_after_function_name' => true,
            'no_spaces_around_offset' => true,
            'no_superfluous_elseif' => true,
            'no_superfluous_phpdoc_tags' => [
                'remove_inheritdoc' => true,
                'allow_hidden_params' => false,
            ],
            'no_trailing_comma_in_singleline' => true,
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
            'no_unneeded_final_method' => true,
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
            'no_whitespace_before_comma_in_array' => true,
            'no_whitespace_in_blank_line' => true,
            'non_printable_character' => true,
            'normalize_index_brace' => true,
            'not_operator_with_space' => false,
            'not_operator_with_successor_space' => false,
            'nullable_type_declaration' => [
                'syntax' => 'union',
            ],
            'nullable_type_declaration_for_default_null_value' => true,
            'numeric_literal_separator' => [
                'override_existing' => true,
            ],
            'object_operator_without_whitespace' => true,
            'octal_notation' => true,
            'operator_linebreak' => true,
            'ordered_attributes' => true,
            'ordered_class_elements' => [
                'case_sensitive' => true,
                'sort_algorithm' => 'alpha',
                'order' => [
                    'use_trait',
                    'case',
                    'constant_public',
                    'constant_protected',
                    'constant_private',
                    'property_public_static',
                    'property_protected_static',
                    'property_private_static',
                    'property_public_readonly',
                    'property_public',
                    'property_protected_readonly',
                    'property_protected',
                    'property_private_readonly',
                    'property_private',
                    'method_public_abstract_static',
                    'method_public_abstract',
                    'method_protected_abstract_static',
                    'method_protected_abstract',
                    'method_private_abstract_static',
                    'method_private_abstract',
                    'construct',
                    'destruct',
                    'magic',
                    'method_public_static',
                    'method_public',
                    'method_protected_static',
                    'method_protected',
                    'method_private_static',
                    'method_private',
                    'phpunit',
                ],
            ],
            'ordered_imports' => [
                'case_sensitive' => true,
                'imports_order' => [
                    'class',
                    'function',
                    'const',
                ],
            ],
            'ordered_interfaces' => [
                'case_sensitive' => true,
            ],
            'ordered_traits' => [
                'case_sensitive' => true,
            ],
            'ordered_types' => [
                'case_sensitive' => true,
            ],
            'php_unit_assert_new_names' => true,
            'php_unit_attributes' => true,
            'php_unit_construct' => true,
            'php_unit_data_provider_method_order' => true,
            'php_unit_data_provider_name' => true,
            'php_unit_data_provider_return_type' => true,
            'php_unit_data_provider_static' => [
                'force' => true,
            ],
            'php_unit_dedicate_assert' => true,
            'php_unit_dedicate_assert_internal_type' => true,
            'php_unit_expectation' => true,
            'php_unit_fqcn_annotation' => false,
            'php_unit_internal_class' => [
                'types' => [
                    'abstract',
                    'final',
                    'normal',
                ],
            ],
            'php_unit_method_casing' => true,
            'php_unit_mock' => true,
            'php_unit_mock_short_will_return' => true,
            'php_unit_namespaced' => true,
            'php_unit_no_expectation_annotation' => true,
            'php_unit_set_up_tear_down_visibility' => true,
            'php_unit_size_class' => true,
            'php_unit_strict' => true,
            'php_unit_test_annotation' => true,
            'php_unit_test_case_static_method_calls' => [
                'call_type' => 'static',
            ],
            'php_unit_test_class_requires_covers' => true,
            'phpdoc_add_missing_param_annotation' => false,
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
            'phpdoc_line_span' => true,
            'phpdoc_list_type' => true,
            'phpdoc_no_access' => true,
            'phpdoc_no_alias_tag' => true,
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
            'phpdoc_scalar' => true,
            'phpdoc_separation' => true,
            'phpdoc_single_line_var_spacing' => true,
            'phpdoc_summary' => true,
            'phpdoc_tag_casing' => true,
            'phpdoc_tag_no_named_arguments' => true,
            'phpdoc_tag_type' => [
                'tags' => [
                    'inheritDoc' => 'inline',
                ],
            ],
            'phpdoc_to_comment' => true,
            'phpdoc_trim' => true,
            'phpdoc_trim_consecutive_blank_line_separation' => true,
            'phpdoc_types' => true,
            'phpdoc_types_order' => [
                'case_sensitive' => true,
                'null_adjustment' => 'always_last',
            ],
            'phpdoc_var_annotation_correct_order' => true,
            'phpdoc_var_without_name' => true,
            'pow_to_exponentiation' => true,
            'protected_to_private' => true,
            'psr_autoloading' => true,
            'random_api_migration' => true,
            'regular_callable_call' => true,
            'return_assignment' => true,
            'return_to_yield_from' => true,
            'return_type_declaration' => true,
            'self_accessor' => true,
            'self_static_accessor' => true,
            'semicolon_after_instruction' => true,
            'set_type_to_cast' => true,
            'short_scalar_cast' => true,
            'simple_to_complex_string_variable' => true,
            'simplified_if_return' => false,
            'simplified_null_return' => true,
            'single_blank_line_at_eof' => true,
            'single_class_element_per_statement' => true,
            'single_import_per_statement' => true,
            'single_line_after_imports' => true,
            'single_line_comment_spacing' => true,
            'single_line_comment_style' => true,
            'single_line_empty_body' => true,
            'single_quote' => [
                'strings_containing_single_quote_chars' => true,
            ],
            'single_space_around_construct' => true,
            'single_trait_insert_per_statement' => true,
            'space_after_semicolon' => [
                'remove_in_empty_for_expressions' => true,
            ],
            'spaces_inside_parentheses' => true,
            'standardize_increment' => true,
            'standardize_not_equals' => true,
            'statement_indentation' => [
                'stick_comment_to_next_continuous_control_statement' => true,
            ],
            'static_lambda' => true,
            'static_private_method' => true,
            'strict_comparison' => true,
            'strict_param' => true,
            'string_implicit_backslashes' => true,
            'string_length_to_empty' => true,
            'string_line_ending' => true,
            'switch_case_semicolon_to_colon' => true,
            'switch_case_space' => true,
            'switch_continue_to_break' => true,
            'ternary_operator_spaces' => true,
            'ternary_to_elvis_operator' => true,
            'ternary_to_null_coalescing' => true,
            'trailing_comma_in_multiline' => [
                'elements' => [
                    'arguments',
                    'array_destructuring',
                    'arrays',
                    'match',
                    'parameters',
                ],
            ],
            'trim_array_spaces' => true,
            'type_declaration_spaces' => true,
            'types_spaces' => true,
            'unary_operator_spaces' => true,
            'use_arrow_functions' => true,
            'void_return' => true,
            'whitespace_after_comma_in_array' => [
                'ensure_single_space' => true,
            ],
            'yield_from_array_to_yields' => true,
            'yoda_style' => [
                'always_move_variable' => true,
                'equal' => false,
                'identical' => false,
                'less_and_greater' => false,
            ],
            'attribute_block_no_spaces' => true,
            'modern_serialization_methods' => true,
            'no_redundant_readonly_property' => false,
            'stringable_for_to_string' => true,
            'single_line_throw' => true,
            'phpdoc_types_no_duplicates' => true,
        ];
    }

    /**
     * @return array<string, array<string, mixed>|bool>
     */
    public static function library(): array
    {
        return [];
    }

    /**
     * @return array<string, array<string, mixed>|bool>
     */
    public static function project(): array
    {
        return [
            'final_class' => true,
        ];
    }

    /**
     * @return array<string, array<string, mixed>|bool>
     */
    public static function tomaschochola(): array
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
