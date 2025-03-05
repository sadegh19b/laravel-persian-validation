<?php

namespace Sadegh19b\LaravelPersianValidation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Sadegh19b\LaravelPersianValidation\Support\Helper;

class PersianNotAccept implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * Persian Not Accept validation:
     *
     * Rejects:
     * - Persian letters
     * - Persian diacritics
     * - ZWNJ
     *
     * Examples:
     * Valid:
     * - English: Hello World
     * - Numbers: 123456
     * - Mixed: Hello 123
     *
     * Invalid:
     * - Persian: سلام
     * - Mixed Persian: Hello سلام
     * - Persian Numbers: ۱۲۳۴۵۶
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (preg_match('/[\p{Arabic}\x{200C}،٫«»٪]/u', (string) $value)) {
            $fail(Helper::translationKey('persian_not_accept'))->translate();
        }
    }
}
