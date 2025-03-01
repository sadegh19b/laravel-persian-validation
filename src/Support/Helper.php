<?php

namespace Sadegh19b\LaravelPersianValidation\Support;

use Illuminate\Support\Facades\Lang;

class Helper
{
    /**
     * Separator for use in regex
     *
     * @param string $input The input string to convert
     * @param string $default The default value if the input is not in the allowed values
     * @param null|array $allows The allow values selected from the ['/', '|', '-', '_', '*', '.', ',', 'space']
     * @param bool $reverseConvert Whether to reverse the conversion
     *
     * @return string The converted string
     */
    public static function separator(string $input, string $default = '-', ?array $allows = null, bool $reverseConvert = false): string
    {
        $allowsMap = ['/', '|', '-', '_', '*', '.', ',', 'space'];
        $replaceMap = [
            '/' => '\/',
            '|' => '\|',
            '*' => '\*',
            '.' => '\.',
            'space' => '\s'
        ];

        if (! is_null($allows)) {
            $invalidValues = array_diff($allows, $allowsMap);

            if (! empty($invalidValues)) {
                throw new \InvalidArgumentException(
                    'Invalid delimiter allows parameter. allowed values: ' . implode(', ', $allowsMap)
                );
            }
        }

        $value = in_array($input, $allows ?? $allowsMap, true) ? $input : $default;

        if ($reverseConvert) {
            return strtr($value, array_flip($replaceMap));
        }

        return strtr($value, $replaceMap);
    }

    /**
     * Get the translation key
     *
     * @param string $key
     *
     * @return string
     */
    public static function translationKey(string $key): string
    {
        return Enum::NAMESPACE . '::' . Enum::FILE_NAME . '.' . $key;
    }

    /**
     * Translate space separator from regex
     *
     * @param  string|null  $separator  The separator to translate
     *
     * @return string The translated separator
     */
    public static function translateSpaceSeparator(?string $separator): string
    {
        return str_replace(['\s', 'space'], Lang::get(self::translationKey( 'space_separator')), $separator);
    }

    /**
     * Convert Persian numbers to English numbers or vice versa
     *
     * @param mixed $input The input to convert
     * @param bool $reverseConvert Whether to convert from English to Persian numbers (default is false)
     *
     * @return string The converted string
     */
    public static function convertPersianNumbers(mixed $input, bool $reverseConvert = false): string
    {
        $fa_num = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $ar_num = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $en_num = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        return $reverseConvert
            ? str_replace($en_num, $fa_num, (string) $input)
            : str_replace([...$fa_num, ...$ar_num], $en_num, (string) $input);
    }

    /**
     * Global convert Persian numbers to English numbers
     *
     * @param string $input The input to convert
     * @param bool $ruleCondition The condition from the rule to check if the input should be converted
     *
     * @return string The converted string
     */
    public static function globalConvertPersianNumbers(mixed $input, bool $ruleCondition): string
    {
        if ($ruleCondition || config(Enum::FILE_NAME . '.convert_persian_numbers', false)) {
            return self::convertPersianNumbers($input);
        }

        return $input;
    }

    /**
     * Check if the date is a valid Persian calendar date.
     *
     * @ref https://fa.wikipedia.org/wiki/گاه‌شماری_هجری_خورشیدی
     *
     * Notes:
     * - Year: 4 digits
     * - Month: 2 digits no required leading zero (1-12)
     * - Day: 2 digits no required leading zero (1-31)
     *
     * Validates:
     * 1. Proper format and length
     * 2. Valid year range
     * 3. Valid month (1-12)
     * 4. Valid day based on month
     *
     * @param string $value The date to validate
     * @param string $separator The separator to use in the regex
     *
     * @return bool True if the date is valid
     */
    public static function validatePersianDate(string $value, string $separator = '/'): bool
    {
        $daysInMonth = [
            1 => 31, 2 => 31, 3 => 31,
            4 => 31, 5 => 31, 6 => 31,
            7 => 30, 8 => 30, 9 => 30,
            10 => 30, 11 => 30, 12 => 29
        ];

        $separator = self::separator($separator, '/');

        $pattern = "/^(1[0-9]{3}){$separator}(0?[1-9]|1[0-2]){$separator}(0?[1-9]|[12][0-9]|3[01])$/";

        if (! preg_match($pattern, $value, $matches)) {
            return false;
        }

        // Extract date components
        $year = (int) $matches[1];
        $month = (int) ($matches[2] ?? substr($value, 4, 2));
        $day = (int) ($matches[3] ?? substr($value, 6, 2));

        // Validate year range
        if ($year < Enum::PERSIAN_YEAR_MIN || $year > Enum::PERSIAN_YEAR_MAX) {
            return false;
        }

        // Get max days for the month
        $maxDays = $daysInMonth[$month];

        // Adjust for leap year if it's month 12
        if ($month === 12 && self::isLeapPersianYear($year)) {
            $maxDays = 30;
        }

        return $day <= $maxDays;
    }

    /**
     * Check if the year is a valid Persian calendar year.
     *
     * Notes:
     * - Year: 4 digits
     *
     * @param string $value The year to validate
     *
     * @return bool True if the date is valid
     */
    public static function validatePersianYear(string $value): bool
    {
        $pattern = "/^(1[0-9]{3})$/";

        if (! preg_match($pattern, $value, $matches)) {
            return false;
        }

        $year = (int) $matches[1];

        // Validate year range
        return ! ($year < Enum::PERSIAN_YEAR_MIN || $year > Enum::PERSIAN_YEAR_MAX);
    }

    /**
     * Check if the given Persian year is a leap year.
     *
     * Algorithm based on the 33-year cycle of the Persian calendar.
     *
     * @param int $year The Persian year to check
     *
     * @return bool True if it's a leap year
     */
    public static function isLeapPersianYear(int $year): bool
    {
        $leapYears = [1, 5, 9, 13, 17, 22, 26, 30];

        return in_array($year % 33, $leapYears);
    }

    /**
     * Convert Gregorian year to Persian (Shamsi) year
     *
     * @param int $gregorianYear The Gregorian year to convert
     * @param int $month The month of Gregorian date (1-12)
     * @param int $day The day of Gregorian date (1-31)
     *
     * @return int The Persian (Shamsi) year
     */
    public static function convertToPersianYear(int $gregorianYear, int $month = 1, int $day = 1): int
    {
        // If date is before March 21 (approximate Persian new year)
        if ($month < 3 || ($month === 3 && $day < 21)) {
            return $gregorianYear - 622;
        }

        return $gregorianYear - 621;
    }

    /**
     * Create persian date example
     *
     * @param string $separator The separator to use in the example
     *
     * @return string The persian date example
     */
    public static function persianDateExample(string $separator = '/'): string
    {
        $separator = self::separator($separator, '/', reverseConvert: true);
        $separator = str_replace('\s', ' ', $separator);

        return self::convertToPersianYear(date('Y'), date('m'), date('d')) . "{$separator}01{$separator}01";
    }

    /**
     * Get list of Persian week days
     *
     * @return array<string>
     */
    public static function getPersianWeekDays(): array
    {
        return [
            'شنبه',
            'یکشنبه',
            'دوشنبه',
            'سه‌شنبه',
            'چهارشنبه',
            'پنج‌شنبه',
            'جمعه',
        ];
    }

    /**
     * Get list of Persian months
     *
     * @return array<string>
     */
    public static function getPersianMonths(): array
    {
        return [
            'فروردین',
            'اردیبهشت',
            'خرداد',
            'تیر',
            'مرداد',
            'شهریور',
            'مهر',
            'آبان',
            'آذر',
            'دی',
            'بهمن',
            'اسفند',
        ];
    }

    /**
     * Get list of valid Iranian phone area codes by province
     *
     * @ref https://en.wikipedia.org/wiki/Telephone_numbers_in_Iran#Area_code
     *
     * @return array<string>
     */
    public static function getIranianPhoneAreaCodes(): array
    {
        return [
            '021', // Tehran
            '026', // Alborz
            '025', // Qom
            '011', // Mazandaran
            '013', // Gilan
            '017', // Golestan
            '041', // East Azerbaijan
            '044', // West Azerbaijan
            '045', // Ardabil
            '024', // Zanjan
            '087', // Kurdistan
            '081', // Hamadan
            '083', // Kermanshah
            '084', // Ilam
            '066', // Lorestan
            '061', // Khuzestan
            '038', // Chaharmahal and Bakhtiari
            '074', // Kohgiluyeh and Boyer-Ahmad
            '031', // Isfahan
            '071', // Fars
            '077', // Bushehr
            '076', // Hormozgan
            '034', // Kerman
            '035', // Yazd
            '054', // Sistan and Baluchestan
            '056', // South Khorasan
            '051', // Razavi Khorasan
            '058', // North Khorasan
            '023', // Semnan
            '086', // Markazi
            '028', // Qazvin
        ];
    }
}
