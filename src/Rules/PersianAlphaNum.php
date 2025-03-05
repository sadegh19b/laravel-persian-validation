<?php

namespace Sadegh19b\LaravelPersianValidation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Sadegh19b\LaravelPersianValidation\Support\Helper;

class PersianAlphaNum implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * Persian AlphaNum (Persian Letters and Numbers) validation:
     *
     * Validates:
     * - Letters: Persian alphabet
     * - Numbers: Persian/Arabic numerals (۰-۹)
     * - Special: Persian diacritics
     * - Space: Regular space and ZWNJ
     *
     * Examples:
     * - Basic: سلام۱۲۳
     * - With special: سَلام۱۲۳
     * - With space: سلام ۱۲۳
     * - With ZWNJ: می‌روم۴۵۶
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! preg_match('/^[\p{Arabic}\x{200C}\s،؛؟!٬٫()«»٪]+$/u', (string) $value)) {
            $fail(Helper::translationKey('persian_alpha_num'))->translate();
        }
    }
}
