<?php

namespace Sadegh19b\LaravelPersianValidation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Sadegh19b\LaravelPersianValidation\Support\Helper;

class IranianNationalId implements ValidationRule
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
     * Iranian National ID (Code Melli):
     *
     * Structure:
     * - 10 digits in total
     * - First 3 digits: City code where the ID was issued
     * - Next 6 digits: Unique identifier within that city
     * - Last digit: Check digit
     *
     * Format options:
     * - Persian number support
     *
     * Examples:
     * - Valid: 0000123456
     * - Valid: ۰۰۰۰۱۲۳۴۵۶
     * - Invalid: 00001234567
     * - Invalid: ۰۰۰۰۱۲۳۴۵۶۷
     *
     * Notes:
     * - Check digit calculation uses a specific pattern of control numbers
     * - Only valid 10-digit numbers are accepted
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = Helper::globalConvertPersianNumbers($value, $this->convertPersianNumbers);

        // Handle codes with leading zeros if length is 8 or 9
        if (strlen($value) >= 8 && strlen($value) < 10) {
            $value = str_pad($value, 10, '0', STR_PAD_LEFT);
        }

        if (! preg_match('/^(?!(\d)\1{9}$)[0-9]{10}$/', $value) ||
            ! $this->isValidNationalCode($value)
        ) {
            $fail(Helper::translationKey('ir_national_id'))->translate();
        }
    }

    /**
     * Check if national code is valid using the official algorithm.
     *
     * Algorithm:
     * 1. Multiply each of first 9 digits by its position weight (10 to 2)
     * 2. Sum all products
     * 3. Calculate remainder of sum divided by 11
     * 4. If remainder < 2, check digit = remainder
     * 5. If remainder >= 2, check digit = 11 - remainder
     *
     * @ref http://www.aliarash.com/article/codemeli/codemeli.htm
     *
     * @param string $value The 10-digit national code
     *
     * @return bool True if the check digit is valid
     */
    protected function isValidNationalCode(string $value): bool
    {
        $sum = 0;
        $values = str_split($value);

        // Calculate sum of products for first 9 digits
        for ($i = 0; $i < 9; $i++) {
            $sum += intval($values[$i]) * (10 - $i);
        }

        $remainder = $sum % 11;
        $checkDigit = ($remainder < 2) ? $remainder : 11 - $remainder;

        return $checkDigit === intval($values[9]);
    }
}
