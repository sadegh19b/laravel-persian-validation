<?php

namespace Sadegh19b\LaravelPersianValidation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Sadegh19b\LaravelPersianValidation\Support\Helper;

class IranianPostalCode implements ValidationRule
{
    /**
     * Create a new rule instance.
     */
    public function __construct(
        protected ?string $separator = null,
        protected bool $convertPersianNumbers = false
    ) {}

    /**
     * Run the validation rule.
     *
     * Iranian Postal Code (Code Posti):
     *
     * Structure:
     * - 10 digits in total
     * - First digit: Province code (non-zero)
     * - Next 9 digits: Area and location codes
     * - No repeated digits in sequence (e.g., 1111)
     *
     * Format options:
     * - With/without separator (default: none)
     * - Persian number support
     *
     * Examples:
     * - Without separator: 1234567890
     * - With separator: 12345-67890
     *
     * @ref https://blog.tapin.ir/معرفی-ساختار-کد-رهگیری-و-کدپستی/
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = Helper::globalConvertPersianNumbers($value, $this->convertPersianNumbers);

        $failMessage = 'ir_postal_code';
        $separator   = '';

        if (! is_null($this->separator)) {
            $separator   = Helper::separator($this->separator, '-');
            $failMessage = 'ir_postal_code_with_separator';
        }

        if (! preg_match("/\b(?!(\d)\1{3})[13-9]{5}{$separator}[0-9]{5}\b/", $value)) {
            $fail(Helper::translationKey($failMessage))->translate([
                'separator' => Helper::translateSpaceSeparator($separator),
            ]);
        }
    }
}
