<?php

namespace Sadegh19b\LaravelPersianValidation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Sadegh19b\LaravelPersianValidation\Support\Helper;

class IranianPhoneAreaCode implements ValidationRule
{
    /**
     * Create a new rule instance.
     */
    public function __construct(
        protected bool $convertPersianNumbers = false
    ) {}

    /**
     * Run the validation rule.
     *
     * Iranian Phone Area Code validation:
     *
     * Validates:
     * - Valid Iranian area code format
     * - Must be in the list of valid Iranian area codes
     *
     * Format options:
     * - Persian number support
     *
     * Format:
     * - 3 digits (e.g., 021, 026, 031)
     *
     * Examples:
     * - Valid: 021 (Tehran)
     * - Valid: 031 (Isfahan)
     * - Valid: ۰۲۱ (Persian digits)
     * - Invalid: 099
     * - Invalid: 1234
     *
     * Notes:
     * - Area codes are fixed length (3 digits)
     * - Must be from official Iranian area code list
     *
     * @ref https://en.wikipedia.org/wiki/Telephone_numbers_in_Iran#Area_code
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = Helper::globalConvertPersianNumbers($value, $this->convertPersianNumbers);

        if (! in_array($value, Helper::getIranianPhoneAreaCodes(), true)) {
            $fail(Helper::translationKey('ir_phone_area_code'))->translate([
                'example'   => '021',
            ]);
        }
    }
}
