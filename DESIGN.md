# Design

This package exposes strict PHP CS Fixer rule arrays.

`PHP85::strictRules()` is the shared PHP 8.5 strict policy.
`PHP85::projectRuleOverrides()` contains project-specific rule replacements.

Use `array_replace()` for target overrides.
Do not use recursive merging for rule configuration unless a specific rule requires it and the behavior is documented.
