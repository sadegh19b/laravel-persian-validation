<?php

namespace Sadegh19b\LaravelPersianValidation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Sadegh19b\LaravelPersianValidation\Support\Helper;

class PersianDateBetweenOrEqualYear implements ValidationRule
{
    /**
     * Create a new rule instance.
     */
    public function __construct(
        protected int|string $startYear,
        protected int|string $endYear,
        protected string $separator = '/',
        protected bool $convertPersianNumbers = false
    ) {}

    /**
     * Run the validation rule.
     *
     * Persian Date Between or Equal Years validation:
     *
     * Validates:
     * - Validates if a year falls between two given Persian years
     * - Years must be in valid Persian calendar range
     *
     * Format options:
     * - Persian number support
     * - Separator for value (default: '/')
     *
     * Year format:
     * - 4 digits
     *
     * Examples:
     * - Value: 1402/01/01
     * - Start: 1401
     * - End: 1403
     *
     * Notes:
     * - Start year must be before or equal of end year
     * - Both start and end years must be valid Persian years
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = Helper::globalConvertPersianNumbers($value, $this->convertPersianNumbers);

        if (! Helper::validatePersianYear($this->startYear) ||
            ! Helper::validatePersianYear($this->endYear)
        ) {
            throw new \InvalidArgumentException('Invalid start/end year format.');
        }

        $cleanValueYear = (int) substr($value, 0, 4);

        if (! Helper::validatePersianDate($value, $this->separator) ||
            $cleanValueYear < $this->startYear || $cleanValueYear > $this->endYear
        ) {
            $fail(Helper::translationKey('persian_date_between_or_equal_year'))->translate([
                'startYear' => $this->startYear,
                'endYear'   => $this->endYear,
            ]);
        }
    }
}
