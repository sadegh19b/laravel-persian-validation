# Upgrade Guide

## Upgrading To 2.0 From 1.x

### Minimum PHP Version
The new minimum PHP version is now 8.2.

### Minimum Laravel Version
Laravel Persian Validation 2.0 requires Laravel 11.0 or greater.

### Core Changes
The package now supports two ways to use validation rules:

1. **Using Rule Objects (New Recommended Way)**
   ```php
   use Sadegh19b\LaravelPersianValidation\Rules\PersianAlpha;
   use Sadegh19b\LaravelPersianValidation\Rules\IranianMobile;

   $request->validate([
       'name' => [new PersianAlpha],
       'mobile' => [new IranianMobile],
   ]);
   ```

2. **Using String Rules (Legacy Way)**
   ```php
   $request->validate([
       'name' => 'persian_alpha',
       'mobile' => 'ir_mobile',
   ]);
   ```

The Rule Objects approach provides better IDE support, type-hinting, and allows for more complex rule configurations.

### Rule Changes

The validation rules have been significantly enhanced in version 2.0:

- All rules now provide more descriptive and user-friendly error messages
- Validation logic has been improved for better accuracy and consistency
- Rule parameters are now more flexible and configurable (see the [readme](README.md) for more details)
- Rules support additional options like Persian number conversion and custom separators
- Each rule has clear documentation of supported formats and examples
- Rules follow consistent patterns for parameter handling and validation

#### New Rules
- `persian_day`: Validates Persian calendar day names
- `persian_month`: Validates Persian calendar month names
- `persian_date_between_year`: Validates dates between specific Persian years
- `persian_date_between_or_equal_year`: Validates dates between or equal to specific Persian years
- `persian_date_between_or_equal`: Validates dates between or equal to specific Persian dates

#### Renamed Rules
- `shamsi_date` → `persian_date`
- `shamsi_date_between` → `persian_date_between`
- `ir_national_code` → `ir_national_id`
- `ir_phone_code` → `ir_phone_area_code`
- `ir_sheba` → `ir_iban`

#### Removed Rules
- `ir_phone_with_code` (Use `ir_phone` with options)
- `a_url`
- `a_domain`

### Error Messages
Error messages have been updated to be more descriptive and user-friendly. If you have published the language files, you should review and update them.

### Testing
The testing framework has been updated to PHPUnit 11. You may need to update your test cases if you've extended the package's test classes.
