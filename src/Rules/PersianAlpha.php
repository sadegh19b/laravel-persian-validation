<?php

namespace Sadegh19b\LaravelPersianValidation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Sadegh19b\LaravelPersianValidation\Support\Helper;

class PersianAlpha implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * Persian Alpha (Persian Letters) validation:
     *
     * Validates:
     * - Persian letters
     * - Persian diacritics
     * - Spaces and ZWNJ
     *
     * Examples:
     * - Basic: سلام
     * - With special: سَلام
     * - With space: سلام خوبی
     * - With ZWNJ: می‌روم
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! preg_match('/^[\p{Arabic}\x{200C}\s،؛؟!٬٫()«»٪]+$/u', (string) $value) || preg_match('/[0-9۰-۹٠-٩]/u', (string) $value)) {
            $fail(Helper::translationKey('persian_alpha'))->translate();
        }
    }
}
