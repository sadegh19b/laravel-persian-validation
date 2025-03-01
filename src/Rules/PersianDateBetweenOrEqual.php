<?php

namespace Sadegh19b\LaravelPersianValidation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Sadegh19b\LaravelPersianValidation\Support\Helper;

class PersianDateBetweenOrEqual implements ValidationRule
{
    public function __construct(
        protected string $startDate,
        protected string $endDate,
        protected string $separator = '/',
        protected bool $convertPersianNumbers = false
    ) {}

    /**
     * Run the validation rule.
     *
     * Persian Date Between or Equal validation:
     *
     * Validates:
     * - Validates if a date falls between two given Persian dates
     * - Dates must be in valid Persian calendar format
     * - Handles leap years automatically
     *
     * Format options:
     * - Separator (default: '/')
     * - Persian number support
     *
     * Date format:
     * - Year: 4 digits
     * - Month: 2 digits with not required leading zeros (01-12)
     * - Day: 2 digits with not required leading zeros (01-31)
     *
     * Examples:
     * - Date: 1402/01/01
     * - Start: 1401/01/01
     * - End: 1403/12/29
     *
     * Notes:
     * - Start date must be before or equal of end date
     * * - End date must be after or equal of start date
 * - Both start and end dates must be valid Persian dates
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = Helper::globalConvertPersianNumbers($value, $this->convertPersianNumbers);

        if( ! Helper::validatePersianDate($this->startDate, $this->separator) ||
            ! Helper::validatePersianDate($this->endDate, $this->separator)
        ) {
            throw new \InvalidArgumentException('Invalid start/end date format.');
        }

        $cleanValue = (int) str_replace($this->separator, '', $value);
        $cleanStartDate = (int) str_replace($this->separator, '', $this->startDate);
        $cleanEndDate = (int) str_replace($this->separator, '', $this->endDate);

        if(! Helper::validatePersianDate($value, $this->separator) ||
            $cleanValue < $cleanStartDate || $cleanValue > $cleanEndDate
        ) {
            $fail(Helper::translationKey('persian_date_between_or_equal'))->translate([
                'attribute' => $attribute,
                'startDate' => $this->startDate,
                'endDate'   => $this->endDate,
            ]);
        }
    }
}
