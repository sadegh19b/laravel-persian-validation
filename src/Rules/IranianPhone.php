<?php

namespace Sadegh19b\LaravelPersianValidation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Sadegh19b\LaravelPersianValidation\Support\Helper;

class IranianPhone implements ValidationRule
{
    /**
     * Create a new rule instance.
     */
    public function __construct(
        protected bool $withAreaCode = false,
        protected ?string $areaCodeSeparator = null,
        protected ?string $withCountryCodeFormat = null,
        protected bool $convertPersianNumbers = false
    ) {}

    /**
     * Run the validation rule.
     *
     * Iranian Phone Number:
     *
     * Structure:
     * - Base: 8 digits local number
     * - Area Code: 2 digits [1-8] (optional)
     * - Country Code: 98 (optional, can be prefixed with + or 00)
     * - Separator: Can be specified for area code
     *
     * Format options:
     * - With/without area code (default: false)
     * - With/without country code (default: true)
     * - With/without separator (default: none)
     * - Persian number support
     *
     * Examples:
     * - Local: 12345678
     * - With Area: 02112345678
     * - With Area Separator: 021-12345678
     * - International: +982112345678, 00982112345678
     *
     * @ref https://www.sent.dm/resources/IR
     * @ref https://en.wikipedia.org/wiki/Telephone_numbers_in_Iran
     * @ref https://gist.github.com/MoienTajik/acd3dbb359054bd22e06cc97281934eb?permalink_comment_id=4198756#gistcomment-4198756
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $separator = '';

        if ($this->withAreaCode && ! is_null($this->areaCodeSeparator) && is_null($this->withCountryCodeFormat)) {
            $separator = Helper::separator($this->areaCodeSeparator, '-');
        }

        $extendPattern  = "[1-8]{2}{$separator}\d{8}";
        $countryCodeFormatPatterns = [
            'zero'   => "0098{$extendPattern}",
            'plus'   => "\+98{$extendPattern}",
            'normal' => "98{$extendPattern}",
            'all'    => "(0098|\+98|98)?{$extendPattern}",
        ];

        $examples = [
            'zero'           => '00982112345678',
            'plus'           => '+981212345678',
            'normal'         => '981212345678',
            'all'            => '00982112345678, +982112345678, 982112345678',
            'area'           => '02112345678',
            'area_separator' => '021{separator}12345678',
            'simple'         => '12345678',
        ];

        if (! is_null($this->withCountryCodeFormat) &&
            ! array_key_exists($this->withCountryCodeFormat, $countryCodeFormatPatterns)
        ) {
            throw new \InvalidArgumentException('Invalid with country code format. Valid formats are: ' .
                implode(', ', array_keys($countryCodeFormatPatterns)));
        }

        $pattern     = $countryCodeFormatPatterns[$this->withCountryCodeFormat ?? 'all'];
        $example     = $examples[$this->withCountryCodeFormat ?? 'all'];
        $failMessage = 'ir_phone_with_country_code';

        if (is_null($this->withCountryCodeFormat)) {
            $pattern = $this->withAreaCode
                ? "0{$extendPattern}"
                : '\d{8}';

            $example = $examples[
                $this->withAreaCode
                    ? 'area'
                    : 'simple'
            ];

            if (! is_null($this->areaCodeSeparator) && $this->withAreaCode) {
                $example = str_replace([$this->areaCodeSeparator, 'space'], ['{separator}', ' '], $examples['area_separator']);
            }

            $failMessage = $this->withAreaCode
                ? 'ir_phone_with_area_code'
                : 'ir_phone';
        }

        $value = Helper::globalConvertPersianNumbers($value, $this->convertPersianNumbers);

        if (! preg_match("/^{$pattern}$/i", $value)) {
            $fail(Helper::translationKey($failMessage))->translate([
                'attribute' => $attribute,
                'example'   => $example,
            ]);
        }
    }
}
