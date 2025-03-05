<?php

namespace Sadegh19b\LaravelPersianValidation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Lang;
use Sadegh19b\LaravelPersianValidation\Support\Helper;

class PersianDay implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * Persian Day (Shamsi Week Day Name) validation:
     * - Name: Persian day names
     *
     * Examples:
     * - Names: شنبه, یکشنبه, ..., جمعه
     *
     * @ref https://fa.wikipedia.org/wiki/هفته#روزهای_هفته_در_تقویم_هجری_شمسی
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $persianDays = Lang::get(Helper::translationKey('list_of_persian_week_days'));

        if (! is_array($persianDays)) {
            $persianDays = Helper::getPersianWeekDays();
        }

        if (! in_array((string) $value, $persianDays, true)) {
            $fail(Helper::translationKey('persian_day'))->translate([
                'days' => implode(', ', $persianDays),
            ]);
        }
    }
}
