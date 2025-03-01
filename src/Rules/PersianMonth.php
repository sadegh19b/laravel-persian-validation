<?php

namespace Sadegh19b\LaravelPersianValidation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Lang;
use Sadegh19b\LaravelPersianValidation\Support\Helper;

class PersianMonth implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * Persian Month (Shamsi Month Name) validation:
     *
     * Examples:
     * - Names: فروردین, اردیبهشت, ..., اسفند
     *
     * @ref https://fa.wikipedia.org/wiki/ماه‌های_هجری_خورشیدی
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $persianMonths = Lang::get(Helper::translationKey('list_of_persian_months'));

        if (! is_array($persianMonths)) {
            $persianMonths = Helper::getPersianMonths();
        }

        if (! in_array((string) $value, $persianMonths, true)) {
            $fail(Helper::translationKey('persian_month'))->translate([
                'attribute' => $attribute,
                'months' => implode(', ', $persianMonths),
            ]);
        }
    }
}
