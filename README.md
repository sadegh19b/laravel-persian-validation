# Laravel Persian Validation

Laravel Persian Validation provides validation rules for Persian alphabet, numbers and Iranian-specific data formats.
It offers comprehensive validation for Persian (Shamsi) dates, phone numbers, national IDs, bank cards, Sheba
numbers, postal codes, and company identifiers. Supporting Laravel 11+ and PHP 8.2+, it seamlessly integrates with Laravel's validation system.

## Requirement

-   Laravel 11, 12
-   PHP >= 8.2

_If you want to use the package in older versions of Laravel (6, 7, 8, 9, 10) and PHP (>= 7.4), you can use the version
[1.x](https://github.com/sadegh19b/laravel-persian-validation/tree/1.x)._

## Installation

> [!IMPORTANT] > **Read the [upgrade guide](UPGRADE.md) before upgrading to the version 2.0**

Install the package with Composer:

```bash
composer require sadegh19b/laravel-persian-validation
```

## Publish

If you want to change the configs, you can publish the config file.
Run the below command on your terminal (the config file will be published to `config/persian-validation.php`):

```bash
php artisan vendor:publish --tag="persian-validation-config"
```

If you want to use validations custom messages, you can publish language file.
Run the below command on your terminal (the language file will be published to `lang/{locale}/persian-validation.php`. supported locales: `en`, `fa`):

```bash
php artisan vendor:publish --tag="persian-validation-lang"
```

## Configurations

The package provides below configuration options in `config/persian-validation.php`:

-   `register_rules` (default: `true`):
    Enables using the validation rules directly in Laravel's validation syntax. When enabled, you can use rules like:
    `php
    'field' => 'required|persian_alpha'
    `

-   `accept_persian_numbers` (default: `false`):
    Determines whether Persian numbers are accepted alongside English numbers in validation rules that involve numbers. When disabled, only English numbers will be accepted.

## Usage

When the `register_rules` is `true` in configuration:

```php
// Basic usage
$rules = [
    'name' => 'required|persian_alpha',
    'mobile' => 'required|ir_mobile',
    'code_melli' => 'required|ir_national_id',
];

// With parameters
$rules = [
    'birth_date' => ['required', 'persian_date', 'persian_date_between:1370/01/01,1402/12/29'],
    // First param for with area code, and second for area code separator.
    // for example this valid value is 021-12345678
    'phone' => ['required', 'ir_phone:true,-'],
];
```

When the rules not registered in laravel validation container:

```php
use Sadegh19b\LaravelPersianValidation\Rules\PersianAlpha;
use Sadegh19b\LaravelPersianValidation\Rules\IranianMobile;
use Sadegh19b\LaravelPersianValidation\Rules\IranianNationalId;
use Sadegh19b\LaravelPersianValidation\Rules\PersianDate;

// Basic usage
$rules = [
    'name' => ['required', new PersianAlpha],
    'mobile' => ['required', new IranianMobile],
    'code_melli' => ['required', new IranianNationalId],
];

// With parameters
$rules = [
    'birth_date' => ['required', new PersianDate(separator: '-')],
    'phone' => ['required', new IranianPhone(withAreaCode: true, areaCodeSeparator: '-')],
];
```

## Rules

**Note:** All parameters in below tables are in order to be used, must be passed to the rule constructor.

### Persian Text and Numbers

| Rule                    | Description                                                                                                                             |       Parameters        |   Valid Examples    | Invalid Examples |
| ----------------------- | --------------------------------------------------------------------------------------------------------------------------------------- | :---------------------: | :-----------------: | :--------------: |
| `persian_alpha`         | Persian alphabetic characters with diacritics, spaces & ZWNJ (حروف فارسی و علائم نگارشی و فاصله و نیم فاصله)                            |            -            |    سلام، علی‌رضا    |   Hello, Test    |
| `persian_alpha_num`     | Persian alphabetic with diacritics, spaces, ZWNJ & Persian numbers (حروف فارسی و علائم نگارشی و فاصله و اعداد فارسی)                    | `convertPersianNumbers` | سلام۱۲۳، علی‌رضا۴۵۶ | Hello 123, Test  |
| `persian_alpha_eng_num` | Persian alphabetic with diacritics, spaces, ZWNJ, Persian & English numbers (حروف فارسی و علائم نگارشی و فاصله و اعداد فارسی و انگلیسی) | `convertPersianNumbers` | سلام123، علی‌رضا۴۵۶ | Hello 123, Test  |
| `persian_num`           | Only Persian numbers (اعداد فارسی)                                                                                                      |            -            |     ۱۲۳۴۵۶۷۸۹۰      |    1234567890    |
| `persian_not_accept`    | Rejects Persian characters and numbers (موارد فارسی غیرقابل قبول)                                                                       |            -            |   Hello 123, Test   |   سلام، تست۱۲۳   |

#### Parameters Details

-   `convertPersianNumbers` is a boolean parameter that converts Persian numbers to English numbers.
    the default is `false`.

### Persian Dates

| Rule                                 | Description                                                                                    | Parameters                                                   |                                                 Valid Examples                                                 |                                         Invalid Examples                                         |
| ------------------------------------ | ---------------------------------------------------------------------------------------------- | ------------------------------------------------------------ | :------------------------------------------------------------------------------------------------------------: | :----------------------------------------------------------------------------------------------: |
| `persian_date`                       | Validates Shamsi dates (تاریخ شمسی)                                                            | `separator`, `convertPersianNumbers`                         |                                              1403/01/01, ۱۴۰۳/۱/۱                                              |                                            2024/03/20                                            |
| `persian_date_between`               | Validates date is between two Shamsi dates (تاریخ مابین دو تاریخ شمسی)                         | `startDate`, `endDate`, `separator`, `convertPersianNumbers` |                                         1403/01/01 (if between range)                                          |                                  1402/12/29 (if outside range)                                   |
| `persian_date_between_or_equal`      | Validates date is between or equal to two Shamsi dates (تاریخ مابین یا برابر با دو تاریخ شمسی) | `startDate`, `endDate`, `separator`, `convertPersianNumbers` |                                         1403/01/01 (if between range)                                          |                                  1402/12/29 (if outside range)                                   |
| `persian_date_between_year`          | Validates year is between two Shamsi years (تاریخ مابین اسال های شمسی)                         | `startYear`, `endYear`, `separator`, `convertPersianNumbers` |                                         1403/01/01 (if year in range)                                          |                                1402/01/01 (if year outside range)                                |
| `persian_date_between_or_equal_year` | Validates year is between or equal to two Shamsi years (تاریخ مابین یا برابر با سال های شمسی)  | `startYear`, `endYear`, `separator`, `convertPersianNumbers` |                                         1403/01/01 (if year in range)                                          |                                1402/01/01 (if year outside range)                                |
| `persian_day`                        | Validates the persian date name (روزهای هفته)                                                  | -                                                            |                 Allows (`شنبه`, `یکشنبه`, `دوشنبه`, `سه‌شنبه`, `چهارشنبه`, `پنج‌شنبه`, `جمعه`)                 |                  Saturday, Sunday, Monday, Tuesday, Wednesday, Thursday, Friday                  |
| `persian_month`                      | Validates the persian month name (ماه‌های شمسی)                                                | -                                                            | Allows (`فروردین`, `اردیبهشت`, `خرداد`, `تیر`, `مرداد`, `شهریور`, `مهر`, `آبان`, `آذر`, `دی`, `بهمن`, `اسفند`) | January, February, March, April, May, June, July, August, September, October, November, December |

**Note:** The dates validate between 1000 and 1600 years.

**Note:** The validation values for `persian_day` and `persian_month` rules can be customized by modifying the corresponding values in the language file (`lang/{locale}/persian-validation.php`).

#### Parameters Details

-   `convertPersianNumbers` is a boolean parameter that converts Persian numbers to English numbers.
    the default is `false`.

-   `separator` is a string parameter that separates the numbers in the validation rules.
    the default is `/`. (Allows: `/`, `|`, `-`, `_`, `*`, `.`, `,`, `space`)

-   `startDate` and `endDate` are **required** parameters and are Shamsi dates that are used to validate the date is between them and using the `separator`.

-   `startYear` and `endYear` are **required** parameters and are Shamsi years that are used to validate the year is between them and the 4-digit acceptable.

### Phone Numbers

| Rule                 | Description                                      | Parameters                                                                            |             Valid Examples              |    Invalid Examples    |
| -------------------- | ------------------------------------------------ | ------------------------------------------------------------------------------------- | :-------------------------------------: | :--------------------: |
| `ir_mobile`          | Iranian mobile numbers (شماره موبایل)            | `format`, `convertPersianNumbers`                                                     | 09123456789, +989123456789, ۰۹۱۲۳۴۵۶۷۸۹ | 091234567, 08123456789 |
| `ir_phone`           | Iranian landline phone numbers (شماره تلفن ثابت) | `withAreaCode`, `areaCodeSeparator`, `withCountryCodeFormat`, `convertPersianNumbers` |        02112345678, 021-12345678        |  0211234, 09123456789  |
| `ir_phone_area_code` | Iranian state area phone codes (کد استان)        | `convertPersianNumbers`                                                               |              021, 031, ۰۲۱              |       099, 1234        |

#### Parameters Details

-   `convertPersianNumbers` is a boolean parameter that converts Persian numbers to English numbers.
    the default is `false`.

-   `format` is a string parameter to validate mobile number format type.
    the default is `all`.(Allows: `all`, `zero_code`, `plus_code`, `code`, `zero`, `normal`)

    -   `zero_code` => 0098: ex. 00989123456789
    -   `plus_code` => +98: ex. +989123456789
    -   `code` => 98: ex. 989123456789
    -   `zero` => 0: ex. 09123456789
    -   `normal` => ex. 9123456789
    -   `all` => 0098, +98, 98, 0: ex. 00989123456789, +989123456789, 989123456789, 09123456789, 9123456789

-   `withAreaCode` is a boolean parameter that validate the phone with should include area phone code.
    the default is `false`.

-   `areaCodeSeparator` is a string parameter that separates the area code from phone number.
    the default is `null`. (Allows: `/`, `|`, `-`, `_`, `*`, `.`, `,`, `space`)

-   `withCountryCodeFormat` is a string parameter that validate the phone with should include country code and area code.
    the default is `null`. (Allows: `zero`, `plus`, `normal`, `all`)
    -   `zero` => 0098: ex. 009802112345678
    -   `plus` => +98: ex. +9802112345678
    -   `normal` => 98: ex. 9802112345678
    -   `all` => 0098, +98, 98: ex. 009802112345678, +9802112345678, 9802112345678
        <br>**Note:** When use the `withCountryCodeFormat` parameter, the `withAreaCode`is required and set it to `true` and the `areaCodeSeparator` parameter will be ignored.

### National and Company Identifiers

| Rule             | Description                                | Parameters              |      Valid Examples      |     Invalid Examples     |
| ---------------- | ------------------------------------------ | ----------------------- | :----------------------: | :----------------------: |
| `ir_national_id` | Iranian National ID (کد ملی)               | `convertPersianNumbers` |  0013542419, ۰۰۱۳۵۴۲۴۱۹  |  012345678, 0123456789a  |
| `ir_company_id`  | Iranian Company ID (شناسه ملی اشخاص حقوقی) | `convertPersianNumbers` | 14007650912, ۱۴۰۰۷۶۵۰۹۱۲ | 1234567890, 123456789012 |

#### Parameters Details

-   `convertPersianNumbers` is a boolean parameter that converts Persian numbers to English numbers.
    the default is `false`.

### Banking

| Rule                  | Description                                  | Parameters                                         | Valid Examples                                               | Invalid Examples                           |
| --------------------- | -------------------------------------------- | -------------------------------------------------- | ------------------------------------------------------------ | ------------------------------------------ |
| `ir_bank_card_number` | Iranian Bank Card Numbers (شماره کارت بانکی) | `separator`, `convertPersianNumbers`               | 6037991234567890, 6037-9912-3456-7890                        | 6037991234567, 603799123456789a            |
| `ir_iban`             | Iranian IBAN (شماره شبا)                     | `withPrefix`, `separator`, `convertPersianNumbers` | IR062960000000100324200001, IR06-2960-0000-0010-0324-2000-01 | IR062960000000, IR06296000000010032420000a |

#### Parameters Details

-   `convertPersianNumbers` is a boolean parameter that converts Persian numbers to English numbers.
    the default is `false`.

-   `separator` is a string parameter that separates the numbers in the validation rules.
    the default is `null`, and means does not use separator. (Allows: `/`, `|`, `-`, `_`, `*`, `.`, `,`, `space`)

-   `withPrefix` is a boolean parameter that validate should with the prefix (IR) to the Iranian iban (Sheba). The default is `true`.

### Postal Codes

| Rule             | Description                    | Parameters                             | Valid Examples                       | Invalid Examples       |
| ---------------- | ------------------------------ | -------------------------------------- | ------------------------------------ | ---------------------- |
| `ir_postal_code` | Iranian Postal Codes (کد پستی) | `separator`, `convert_persian_numbers` | 1619735744, 16197-35744, ۱۶۱۹۷-۳۵۷۴۴ | 123456789, 12345678901 |

#### Parameters Details

-   `convertPersianNumbers` is a boolean parameter that converts Persian numbers to English numbers.
    the default is `false`.

-   `separator` is a string parameter that separates the numbers in the validation rules.
    the default is `null`, and means does not use separator. (Allows: `/`, `|`, `-`, `_`, `*`, `.`, `,`, `space`)

## License

The Laravel Persian Validation package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT). Please see [License File](LICENSE.md) for more information.
