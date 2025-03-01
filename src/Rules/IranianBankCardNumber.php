<?php

namespace Sadegh19b\LaravelPersianValidation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Sadegh19b\LaravelPersianValidation\Support\Helper;

class IranianBankCardNumber implements ValidationRule
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
     * Iranian Bank Card Number:
     *
     * Structure:
     * - 16 digits in total
     * - First 6 digits: Bank identifier (BIN)
     * - Next 9 digits: Account identifier
     * - Last digit: Check digit (using Luhn algorithm)
     *
     * Format options:
     * - With/without separator (default: none)
     * - Persian number support
     *
     * @ref https://wikiplast.ir/news/17957/پیش-شماره-کارت-شتابی-بانک‎های-کشور
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = Helper::globalConvertPersianNumbers($value, $this->convertPersianNumbers);

        $separator   = '';
        $failMessage = 'ir_bank_card_number';
        $pattern     = "/^[2569]{1}\d{15}$/";

        if (! is_null($this->separator)) {
            $separator   = Helper::separator($this->separator, '-');
            $pattern     = "/^[2569]{1}\d{3}{$separator}\d{4}{$separator}\d{4}{$separator}\d{4}$/";
            $failMessage = 'ir_bank_card_number_with_separator';
        }

        if (! preg_match($pattern, $value) ||
            ! $this->isValidCardNumber(preg_replace("/{$separator}/", '', $value))
        ) {
            $fail(Helper::translationKey($failMessage))->translate([
                'attribute' => $attribute,
                'separator' => Helper::translateSpaceSeparator($separator),
            ]);
        }
    }

    /**
     * Check if bank card number is valid using the Luhn algorithm.
     *
     * Luhn Algorithm:
     * 1. Starting from the rightmost digit, double every second digit
     * 2. If doubling a number of results in a two-digit number, add the digits together
     * 3. Sum all the digits
     * 4. If the total modulo 10 is equal to 0, the card number is valid
     *
     * @ref http://www.aliarash.com/article/creditcart/credit-debit-cart.htm
     *
     * @param  string  $value  The 16-digit bank card number
     *
     * @return bool True if the card number is valid
     */
    protected function isValidCardNumber(string $value): bool
    {
        $cardTotal = 0;
        $length    = 16;
        $cardToArr = str_split($value);

        for ($position = 1; $position <= $length; $position++) {
            $digit   = (int) $cardToArr[$position - 1];
            $doubled = $digit * 2;

            $cardTotal += ($position % 2 !== 0)
                ? ($doubled > 9 ? $doubled - 9 : $doubled)
                : $digit;
        }

        return $cardTotal % 10 === 0;
    }
}
