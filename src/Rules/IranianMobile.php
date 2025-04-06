<?php

namespace Sadegh19b\LaravelPersianValidation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Sadegh19b\LaravelPersianValidation\Support\Helper;

class IranianMobile implements ValidationRule
{
    /**
     * Create a new rule instance.
     */
    public function __construct(
        protected string $format = 'all',
        protected bool $convertPersianNumbers = false
    ) {}

    /**
     * Run the validation rule.
     *
     * Iranian Mobile Number validation:
     *
     * Validates:
     * - Valid Iranian mobile number format
     * - Supports multiple format options
     * - Optional Persian number support
     *
     * Format options:
     * - 'zero_code': With 0098 prefix (00989...)
     * - 'plus_code': With +98 prefix (+989...)
     * - 'code': With 98 prefix (989...)
     * - 'zero': With 0 prefix (09...)
     * - 'normal': Without prefix (9...)
     * - 'all': Accepts all formats (default)
     *
     * Format:
     * - Starts with optional country code (0098, +98, 98)
     * - Followed by 9
     * - Then 9 more digits
     *
     * Examples:
     * - Valid (zero_code): 00989123456789
     * - Valid (plus_code): +989123456789
     * - Valid (code): 989123456789
     * - Valid (zero): 09123456789
     * - Valid (normal): 9123456789
     * - Valid (Persian): ۰۹۱۲۳۴۵۶۷۸۹
     * - Invalid: 091234567 (too short)
     * - Invalid: 0912345678901 (too long)
     * - Invalid: 08123456789 (wrong prefix)
     *
     * Notes:
     * - Total length varies by format (10-14 digits)
     * - All formats must start with 9 after any prefix
     *
     * @ref https://en.wikipedia.org/wiki/Telephone_numbers_in_Iran#Mobile_phones
     * @ref https://gist.github.com/MoienTajik/acd3dbb359054bd22e06cc97281934eb?permalink_comment_id=3844052#gistcomment-3844052
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $extendPattern  = '(0[1-5]|[1 9]\d|2[0-2]|98)\d{7}';
        $formatPatterns = [
            'zero_code' => "00989{$extendPattern}",
            'plus_code' => "\+989{$extendPattern}",
            'code'      => "989{$extendPattern}",
            'zero'      => "09{$extendPattern}",
            'normal'    => "9{$extendPattern}",
            'all'       => "(0|0098|\+98|98)?9{$extendPattern}",
        ];

        $examples = [
            'zero_code' => '00989123456789',
            'plus_code' => '+989123456789',
            'code'      => '989123456789',
            'zero'      => '09123456789',
            'normal'    => '9123456789',
            'all'       => '00989123456789, +989123456789, 989123456789, 09123456789, 9123456789',
        ];

        $failMessages = [
            'zero_code' => 'ir_mobile_with_country_code',
            'plus_code' => 'ir_mobile_with_country_code',
            'code'      => 'ir_mobile_with_country_code',
            'zero'      => 'ir_mobile',
            'normal'    => 'ir_mobile',
            'all'       => 'ir_mobile_with_country_code',
        ];

        if (! array_key_exists($this->format, $formatPatterns)) {
            throw new \InvalidArgumentException('Invalid format. valid formats are: ' .
                implode(', ', array_keys($formatPatterns))
            );
        }

        $value = Helper::globalConvertPersianNumbers($value, $this->convertPersianNumbers);
        $pattern = $formatPatterns[$this->format];

        if (! preg_match("/^{$pattern}$/i", $value)) {
            $fail(Helper::translationKey($failMessages[$this->format]))->translate([
                'example'   => $examples[$this->format],
            ]);
        }
    }
}
