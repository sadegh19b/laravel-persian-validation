<?php

namespace Sadegh19b\LaravelPersianValidation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Sadegh19b\LaravelPersianValidation\Support\Helper;

class IranianIban implements ValidationRule
{
    private const IRAN_IBAN_CODE = '1827'; // (IR = 1827)

    /**
     * Create a new rule instance.
     */
    public function __construct(
        protected bool $withPrefix = true,
        protected ?string $separator = null,
        protected bool $convertPersianNumbers = false
    ) {}

    /**
     * Run the validation rule.
     *
     * Iranian IBAN (Sheba):
     *
     * Structure:
     * - 26 characters in total
     * - First 2 chars: Country code (IR)
     * - Next 2 digits: Check digits
     * - Next 3 digits: Bank identifier
     * - Next 19 digits: Account number
     *
     * Format options:
     * - With/without prefix (default: with prefix)
     * - With/without separator (default: none)
     * - Persian number support
     *
     * Examples:
     * - Without prefix: 062960000000100324200001
     * - With prefix: IR062960000000100324200001
     * - Without separator: IR062960000000100324200001
     * - With separator: IR06-2960-0000-0010-0324-2000-01
     *
     * @ref https://en.wikipedia.org/wiki/International_Bank_Account_Number#Structure
     * @ref https://web.archive.org/web/20210518172456/https://bmi.ir/fa/pages/192/مشخصات ملی شناسه حساب بانکی ایران (شبا)
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = Helper::globalConvertPersianNumbers($value, $this->convertPersianNumbers);

        $separator = '';
        $failMessage = 'ir_iban';

        if ($this->separator) {
            $separator = Helper::separator($this->separator, 'space');
            $failMessage = 'ir_iban_with_separator';
        }

        $extendPattern = "[0-9]{2}{$separator}([0-9]{4}){$separator}([0-9]{4}){$separator}([0-9]{4}){$separator}([0-9]{4}){$separator}([0-9]{4}){$separator}[0-9]{2}";
        $pattern = $this->withPrefix
                ? "IR{$extendPattern}"
                : $extendPattern;

        if (! preg_match("/^{$pattern}$/", $value) ||
            ! $this->isValidIban(preg_replace("/{$separator}/", '', $value))
        ) {
            $fail(Helper::translationKey($failMessage))->translate([
                'separator' => Helper::translateSpaceSeparator($separator),
            ]);
        }
    }

    /**
     * Check if Sheba number is valid using verify IBAN check digits using MOD-97 algorithm.
     *
     * Formula: [CC][CD][BBAN] => Country Code, Check Digits, Basic Bank Account Number
     */
    protected function isValidIban(string $value): bool
    {
        $checkDigits = substr($value, 2, 2);
        $bban = substr($value, 4);

        if (! str_starts_with($value, 'IR')) {
            $checkDigits = substr($value, 0, 2);
            $bban = substr($value, 2);
        }

        // Move country code and check digits to end
        $iban = $bban . self::IRAN_IBAN_CODE . $checkDigits;

        // Split into chunks and calculate mod-97
        $chunks = str_split($iban, 7);
        $remainder = 0;

        foreach ($chunks as $chunk) {
            $remainder = (int) ($remainder . $chunk) % 97;
        }

        return $remainder === 1;
    }
}
