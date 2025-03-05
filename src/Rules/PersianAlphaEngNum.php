<?php

namespace Sadegh19b\LaravelPersianValidation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Sadegh19b\LaravelPersianValidation\Support\Helper;

class PersianAlphaEngNum implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * Persian Alpha with English Numbers (Persian Letters and Persian/English Numbers) validation:
     *
     * Validates:
     * - Letters: Persian alphabet
     * - Numbers: Both Persian (۰-۹) and English (0-9) numerals
     * - Special: Persian diacritics
     * - Space: Regular space and ZWNJ
     *
     * Examples:
     * - Basic: سلام123
     * - Mixed: Hello سلام 123۴۵۶
     * - With special: سَلام123
     * - With space: سلام 123
     * - With ZWNJ: می‌روم456
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! preg_match('/^[\p{Arabic}\x{200C}\s0-9،؛؟!٬٫()«»٪]+$/u', (string) $value)) {
            $fail(Helper::translationKey('persian_alpha_eng_num'))->translate();
        }
    }
}
