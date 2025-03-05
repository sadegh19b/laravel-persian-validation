<?php

namespace Sadegh19b\LaravelPersianValidation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Sadegh19b\LaravelPersianValidation\Support\Helper;

class PersianDate implements ValidationRule
{
    /**
     * Create a new rule instance.
     */
    public function __construct(
        protected string $separator = '/',
        protected bool $convertPersianNumbers = false
    ) {}

    /**
     * Run the validation rule.
     *
     * Persian Date (Shamsi Date) structure:
     * - Year: 4 digits
     * - Month: 2 digits no required leading zero (1-12)
     * - Day: 2 digits no required leading zero (1-31)
     *
     * Format options:
     * - Separator (default: '/')
     * - Persian number support
     *
     * Examples:
     * 1403/01/01 or 1403/1/1
     *
     * Notes:
     * - Months 1-6: 31 days
     * - Months 7-11: 30 days
     * - Month 12: 29 days (30 in leap years)
     *
     * @ref https://fa.wikipedia.org/wiki/گاه‌شماری_هجری_خورشیدی
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = Helper::globalConvertPersianNumbers($value, $this->convertPersianNumbers);

        if (! Helper::validatePersianDate($value, $this->separator)) {
            $fail(Helper::translationKey('persian_date'))->translate([
                'example' => Helper::persianDateExample($this->separator),
            ]);
        }
    }
}
