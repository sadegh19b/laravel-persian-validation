# Laravel Persian Validation

Laravel Persian Validation Provides validation for Persian alphabet, number and etc.

## Requirement

* Laravel 6, 7, 8, 9, 10, 11
* PHP 7.4 , 8, 8.1, 8.2

## Install

Via Composer

``` bash
composer require sadegh19b/laravel-persian-validation
```

## Publishing Language Files
To publish the language files for custom validation messages, execute the following command. This will place the file at resources/lang/{locale}/persian-validation.php.

``` bash
php artisan vendor:publish --provider="Sadegh19b\LaravelPersianValidation\PersianValidationServiceProvider"
```

## Usage

You can access to validation rules by passing the rules key according blew following table:

| Rules | Descriptions | Acceptable Examples
| --- | --- |  --- |
| persian_alpha | Persian alphabet | صادق
| persian_num | Persian numbers | ۱۲۳۴
| persian_alpha_num | Persian alphabet and numbers |صادق۱۲۳۴
| persian_alpha_eng_num | Persian alphabet and numbers with english numbers |صادق۱۲34
| persian_not_accept | Doesn't accept Persian alphabet and numbers | cant be persian
| shamsi_date | Check shamsi (jalali) date with format(Y/m/d) or format(Y-m-d)  | 1373/3/19
| shamsi_date:persian | Check shamsi (jalali) date with format(Y/m/d) or format(Y-m-d) with persian number | ۱۳۷۳/۳/۱۹
| shamsi_date_between:1300,1400 | Check shamsi (jalali) date with format(Y/m/d) or format(Y-m-d) between years | 1373/3/19
| shamsi_date_between:1300,1400,persian | Check shamsi (jalali) date with format(Y/m/d) or format(Y-m-d) between years with persian number | ۱۳۷۳/۳/۱۹
| ir_mobile | Iranian mobile numbers | 00989173456789, +989173456789, 989173456789, 09173456789, 91712345678
| ir_mobile:zero_code | Iranian mobile numbers with double zero country code | 00989173456789
| ir_mobile:plus | Iranian mobile numbers with plus country code | +989173456789
| ir_mobile:code | Iranian mobile numbers with country code | 989173456789
| ir_mobile:zero | Iranian mobile numbers starts with zero | 09173456789
| ir_mobile:without_zero | Iranian mobile numbers without first zero | 9173456789
| ir_phone | Iranian phone numbers | 37236445
| ir_phone_code | Iranian phone area code | 077, 021, ...
| ir_phone_with_code | Iranian phone number with area code | 07737236445
| ir_postal_code | Iranian postal code | 1619735744, 16197-35744
| ir_postal_code:seprate | Iranian postal code sperated | 16197-35744
| ir_postal_code:without_seprate | Iranian postal code without seprate | 1619735744
| ir_bank_card_number | Iranian bank payment card numbers | 6274129005473742
| ir_bank_card_number:seprate | Iranian bank payment card numbers seprate between digits with dash | 6274-1290-0547-3742
| ir_bank_card_number:space | Iranian bank payment card numbers seprate between digits with space | 6274 1290 0547 3742
| ir_sheba | Iranian Sheba numbers | IR062960000000100324200001
| ir_national_code | Iran melli code | 0013542419
| a_url | Check correct URL | http://google.com, https://www.google.com
| a_domain | Check correct Domain | www.google.com, google.com
| ir_company_id | Iranian National Legal Entity Identifier (Shenase Melli Ashkhas Hoghoghi) | 14007650912


### Persian Alpha
Accept Persian language alphabet according to standard Persian, this is the way you can use this validation rule:

``` php
$input = [ 'فارسی' ];

$rules = [ 'persian_alpha' ];

Validator::make( $input, $rules );
```

### Persian numbers
Validate Persian standard numbers (۰۱۲۳۴۵۶۷۸۹):

``` php
$input = [ '۰۱۲۳۴۵۶۷۸۹' ];

$rules = [ 'persian_num' ];

Validator::make( $input, $rules );
```

### Persian Alpha Num
Validate Persian alpha num:

``` php
$input = [ '۰فارسی۱۲۳۴۵۶۷۸۹' ];

$rules = [ 'persian_alpha_num' ];

Validator::make( $input, $rules );
```

### Persian Alpha Eng Num
Validate Persian alpha num with english num:

``` php
$input = [ '۰فارسی۱۲۳۴۵6789' ];

$rules = [ 'persian_alpha_eng_num' ];

Validator::make( $input, $rules );
```

### Shamsi Date
Validate shamsi (jalali) date:

``` php
$input = [ '1373/3/19' ];

$rules = [ 'shamsi_date' ];

Validator::make( $input, $rules );
```

### Shamsi Date Between
Validate shamsi (jalali) date between years:

``` php
$input = [ '1373/3/19' ];

$rules = [ 'shamsi_date_between:1300,1400' ];

Validator::make( $input, $rules );
```

### Iran mobile phone
Validate Iranian mobile numbers (Irancell, Rightel, Hamrah-e-aval, ...):

``` php
$input = [ '09381234567' ];

$rules = [ 'ir_mobile' ];

Validator::make( $input, $rules );
```

### Sheba number
Validate Iranian bank sheba numbers:

``` php
$input = [ 'IR062960000000100324200001' ];

$rules = [ 'ir_sheba' ];

Validator::make( $input, $rules );
```

### Iran national code
Validate Iranian national code (Melli Code):

``` php
$input = [ '3240175800' ];

$rules = [ 'ir_national_code' ];

Validator::make( $input, $rules );
```

### Payment card number
Validate Iranian bank payment card numbers:

``` php
$input = [ '6274129005473742' ];

$rules = [ 'ir_bank_card_number' ];

Validator::make( $input, $rules );
```

### Iran postal code
Validate Iranian postal code:

``` php
$input = [ '167197-35744' ];

$rules = [ 'ir_postal_code' ];

Validator::make( $input, $rules );

or

$input = [ '16719735744' ];

$rules = [ 'ir_postal_code' ];

Validator::make( $input, $rules );

```

### Iran company id
Validate Iranian National Legal Entity Identifier (Shenase Melli Ashkhas Hoghoghi)

``` php
$input = [ '14007650912' ];

$rules = [ 'ir_company_id' ];

Validator::make( $input, $rules );
```

## More
Full list of Persian Validation rules usage:

``` php
Validator::make( $request->all(), [

  'name'          => 'persian_alpha|unique|max:25', // Validate Persian alphabet, unique and max to 25 characters

  'age'           => 'persian_num|required',  // Validate Persian numbers and check it's required

  'city'          => 'persian_alpha_num|min:10',  // Validate persian alphabet & numbers at least 10 digit accepted

  'address'       => 'persian_alpha_eng_num',  // Validate persian alphabet & numbers with english numbers

  'birthday'      => 'shamsi_date', // Validate shamsi date 

  'start_date'    => 'shamsi_date_between:1300,1400', // Validate shamsi date between years

  'mobile'        => 'ir_mobile', // Validate mobile number

  'sheba_number'  => 'ir_sheba', // Validate sheba number of bank account

  'melli_code'    => 'ir_national_code',  // Validate melli code number

  'latin_name'    => 'persian_not_accept',  // Validate alphabet and doesn't contain Persian alphabet or number

  'url'           => 'a_url', // Validate url

  'domain'        => 'a_domain',  // Validate domain

  'phone'         => 'ir_phone', // Validate phone number
  
  'area_code'     => 'ir_phone_code', // Validate phone area code
  
  'phone_code'    => 'ir_phone_with_code', // Validate phone number with area code

  'card_number'   => 'ir_bank_card_number', // Validate payment card number

  'postal_code'   => 'ir_postal_code' // validate iran postal code format
  
  'company_id'    => 'ir_company_id',  // Iranian National Legal Entity Identifier (Shenase Melli Ashkhas Hoghoghi)

]);
```

## License
The [MIT license](http://opensource.org/licenses/MIT) (MIT). Please see [License File](https://github.com/sadegh19b/laravel-persian-validation/blob/master/LICENSE.md) for more information.
