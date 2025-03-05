<?php

namespace Sadegh19b\LaravelPersianValidation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Sadegh19b\LaravelPersianValidation\Support\Helper;

class IranianCompanyId implements ValidationRule
{
    /**
     * Control numbers used in the check digit calculation.
     * Pattern: 29, 27, 23, 19, 17 (repeating)
     */
    protected const CONTROL_NUMBERS = [29, 27, 23, 19, 17];

    /**
     * Create a new rule instance.
     */
    public function __construct(
        protected bool $convertPersianNumbers = false
    ) {}

    /**
     * Run the validation rule.
     *
     * Iranian Company ID (Shenase Melli Ashkhas Hoghoghi):
     *
     * Structure:
     * - 11 digits in total
     * - First 4 digits: Registration city code
     * - Next 6 digits: Unique identifier within that city
     * - Last digit: Check digit
     *
     * Format options:
     * - Persian number support
     *
     * Examples:
     * - Valid: 00001234567890
     * - Valid: ۰۰۰۰۱۲۳۴۵۶۷۸۹۰
     * - Invalid: 00001234567891
     * - Invalid: ۰۰۰۰۱۲۳۴۵۶۷۸۹۱
     *
     * Notes:
     * - Check digit calculation uses a specific pattern of control numbers
     * - Only valid 11-digit numbers are accepted
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = Helper::globalConvertPersianNumbers($value, $this->convertPersianNumbers);

        if (! preg_match('/^(?!(\d)\1{10}$)[0-9]{11}$/', $value) ||
            ! $this->isValidCompanyId($value)
        ) {
            $fail(Helper::translationKey('ir_company_id'))->translate();
        }
    }

    /**
     * Check if company ID is valid using the official algorithm.
     *
     * Algorithm:
     * 1. Add the tens digit + 2 (usually 11) to each of first 10 digits
     * 2. Multiply each sum by its control number (29,27,23,19,17 pattern)
     * 3. Sum all products
     * 4. Calculate remainder of sum divided by 11
     * 5. If remainder = 10, check digit should be 0
     * 6. Otherwise, check digit should equal remainder
     *
     * @ref http://www.aliarash.com/article/shenasameli/shenasa_meli.htm
     *
     * @param  string  $value  The 11-digit company ID
     *
     * @return bool True if the check digit is valid
     */
    protected function isValidCompanyId(string $value): bool
    {
        $values    = str_split($value);
        $tensDigit = intval($values[9]); // Get the tens digit (10th position)
        $sum       = 0;

        // Calculate sum of products for first 10 digits
        for ($i = 0; $i < 10; $i++) {
            // Add tensDigit + 2 to each digit before multiplying
            $digit = intval($values[$i]) + ($tensDigit + 2);
            // Use modulo to repeat the control numbers pattern
            $controlNumber = self::CONTROL_NUMBERS[$i % 5];
            $sum += $digit * $controlNumber;
        }

        $remainder = $sum % 11;
        // If remainder is 10, check digit should be 0
        $checkDigit = ($remainder === 10) ? 0 : $remainder;

        return $checkDigit === intval($values[10]);
    }
}
