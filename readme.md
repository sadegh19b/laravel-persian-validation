# Laravel Persian Validation

Laravel Persian Validation Provides validation for Persian alphabet, number and etc.

## Requirement

* Laravel 6, 7, 8
* PHP 7.2 >=

## Install

Via Composer

``` bash
composer require sadegh19b/laravel-persian-validation
```

## vendor:publish
You can run vendor:publish command to have custom lang file of package on this path ( resources/lang/{locale}/persian-validation.php )
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
| ir_mobile | Iranian mobile numbers | 00989173456789, +989173456789, 989173456789, 09173456789, 91712345678
| ir_phone | Iranian phone numbers | 37236445
| ir_phone_code | Iranian phone area code | 077, 021, ...
| ir_phone_with_code | Iranian phone number with area code | 07737236445
| ir_postal_code | Iranian postal code. | 1619735744, 16197-35744
| ir_card_number | Iranian bank payment card numbers. | 6274129005473742
| ir_sheba | Iranian Sheba numbers | IR062960000000100324200001
| ir_national_code | Iran melli code | 0013542419
| a_url | Check correct URL | http://google.com, https://www.google.com
| a_domain | Check correct Domain | www.google.com, google.com


### Persian Alpha
Accept Persian language alphabet according to standard Persian, this is the way you can use this validation rule:

```
$input = [ 'فارسی' ];

$rules = [ 'persian_alpha' ];

Validator::make( $input, $rules );
```

### Persian numbers
Validate Persian standard numbers (۰۱۲۳۴۵۶۷۸۹):

```
$input = [ '۰۱۲۳۴۵۶۷۸۹' ];

$rules = [ 'persian_num' ];

Validator::make( $input, $rules );
```

### Persian Alpha Num
Validate Persian alpha num:

```
$input = [ '۰فارسی۱۲۳۴۵۶۷۸۹' ];

$rules = [ 'persian_alpha_num' ];

Validator::make( $input, $rules );
```

### Persian Alpha Eng Num
Validate Persian alpha num with english num:

```
$input = [ '۰فارسی۱۲۳۴۵6789' ];

$rules = [ 'persian_alpha_eng_num' ];

Validator::make( $input, $rules );
```

### Iran mobile phone
Validate Iranian mobile numbers (Irancell, Rightel, Hamrah-e-aval, ...):

```
$input = [ '09381234567' ];

$rules = [ 'ir_mobile' ];

Validator::make( $input, $rules );
```

### Sheba number
Validate Iranian bank sheba numbers:

```
$input = [ 'IR062960000000100324200001' ];

$rules = [ 'ir_sheba' ];

Validator::make( $input, $rules );
```

### Iran national code
Validate Iranian national code (Melli Code):

```
$input = [ '3240175800' ];

$rules = [ 'ir_national_code' ];

Validator::make( $input, $rules );
```

### Payment card number
Validate Iranian bank payment card numbers:

```
$input = [ '6274129005473742' ];

$rules = [ 'ir_card_number' ];

Validator::make( $input, $rules );
```

### Iran postal code
Validate Iranian postal code:

```
$input = [ '167197-35744' ];

$rules = [ 'ir_postal_code' ];

Validator::make( $input, $rules );

or

$input = [ '16719735744' ];

$rules = [ 'ir_postal_code' ];

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

  'mobile'        => 'ir_mobile', // Validate mobile number

  'sheba_number'  => 'ir_sheba', // Validate sheba number of bank account

  'melli_code'    => 'ir_national_code',  // Validate melli code number

  'latin_name'    => 'persian_not_accept',  // Validate alphabet and doesn't contain Persian alphabet or number

  'url'           => 'a_url', // Validate url

  'domain'        => 'a_domain',  // Validate domain

  'phone'         => 'ir_phone', // Validate phone number
  
  'area_code'     => 'ir_phone_code', // Validate phone area code
  
  'phone_code'    => 'ir_phone_with_code', // Validate phone number with area code

  'card_number'   => 'ir_card_number', // Validate payment card number

  'postal_code'   => 'ir_postal_code' // validate iran postal code format

]);
```

## License
The [MIT license](http://opensource.org/licenses/MIT) (MIT). Please see [License File](https://github.com/sadegh19b/laravel-persian-validation/blob/master/LICENSE.md) for more information.
