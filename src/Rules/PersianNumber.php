<?php

namespace Sadegh19b\LaravelPersianValidation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Sadegh19b\LaravelPersianValidation\Support\Helper;

class PersianNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * Persian Number validation:
     *
     * Validates:
     * - Only Persian/Arabic numerals are allowed
     * - No other characters are permitted
     *
     * Format:
     * - Persian/Arabic numerals (۰-۹, ٠-٩)
     *
     * Examples:
     * - Valid: ۱۲۳۴۵
     * - Valid: ٠١٢٣٤
     * - Invalid: 12345
     * - Invalid: ۱۲a۳۴
     *
     * Notes:
     * - Both Persian (۰-۹) and Arabic (٠-٩) numerals are accepted
     * - No spaces or other characters are allowed
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! preg_match('/^[۰-۹٠-٩]+$/u', (string) $value)) {
            $fail(Helper::translationKey('persian_num'))->translate([
                'attribute' => $attribute
            ]);
        }
    }
}
