<?php
return [
    /*
     * Whether to register the Persian validation rules in Laravel validation container.
     * When enabled, you can use the rules directly in validation strings like:
     * 'field' => 'required|persian_alpha'
     *
     * See the README for a complete list of available validation rules.
     */
    'register_rules' => true,

    /*
     * Whether globally to convert Persian/Arabic numbers to English numbers in all persian validation rules that involve numbers.
     * When enabled, rules will accept both Persian/Arabic (۰-۹, ٠-٩) and English (0-9) numbers.
     * When disabled, only English numbers will be accepted.
     */
    'convert_persian_numbers' => false,
];
