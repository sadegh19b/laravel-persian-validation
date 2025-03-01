<?php

namespace Sadegh19b\LaravelPersianValidation\Tests\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sadegh19b\LaravelPersianValidation\Rules\IranianPhone;
use Sadegh19b\LaravelPersianValidation\Tests\TestCase;

class IranianPhoneTest extends TestCase
{
    protected ValidationRule $rule;
    protected ValidationRule $ruleWithAreaCode;
    protected ValidationRule $ruleAreaCodeSeparator;
    protected ValidationRule $rulePersianNumbers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rule = new IranianPhone();
        $this->ruleWithAreaCode = new IranianPhone(withAreaCode: true);
        $this->ruleAreaCodeSeparator = new IranianPhone(withAreaCode: true, areaCodeSeparator: '-');
        $this->rulePersianNumbers = new IranianPhone(convertPersianNumbers: true);
    }

    #[Test]
    #[DataProvider('validProvider')]
    public function it_passes_for_valid_data(string $value): void
    {
        $this->assertValidationPasses($value, $this->rule);
    }

    #[Test]
    #[DataProvider('invalidProvider')]
    public function it_fails_for_invalid_data(string $value): void
    {
        $this->assertValidationFails($value, 'ir_phone', $this->rule);
    }

    public static function validProvider(): array
    {
        return [
            ['22334455'],
            ['37236445'],
            ['45678901'],
            ['56789012'],
            ['67890123'],
            ['78901234'],
            ['89012345'],
            ['90123456'],
        ];
    }

    public static function invalidProvider(): array
    {
        return [
            ['2233445'],     // Invalid: too short (7 digits)
            ['223344556'],   // Invalid: too long (9 digits)
            ['22-33-44-55'], // Invalid: contains dashes
            ['2233 4455'],   // Invalid: contains spaces
            ['2233a445'],    // Invalid: contains letters
            [''],            // Invalid: empty string
            ['abcdefgh'],    // Invalid: non-numeric
        ];
    }

    #[Test]
    #[DataProvider('validWithAreaCodeProvider')]
    public function it_passes_for_valid_with_area_code(string $value): void
    {
        $this->assertValidationPasses($value, $this->ruleWithAreaCode);
    }

    #[Test]
    #[DataProvider('invalidWithAreaCodeProvider')]
    public function it_fails_for_invalid_with_area_code(string $value): void
    {
        $this->assertValidationFails($value, 'ir_phone_with_area_code', $this->ruleWithAreaCode);
    }

    public static function validWithAreaCodeProvider(): array
    {
        return [
            ['02112345678'], // Tehran
            ['03112345678'], // Isfahan
            ['05112345678'], // Mashhad
            ['07112345678'], // Shiraz
            ['01112345678'], // Mazandaran
            ['04112345678'], // Tabriz
            ['08112345678'], // Hamedan
            ['06112345678'], // Ahvaz
        ];
    }

    public static function invalidWithAreaCodeProvider(): array
    {
        return [
            ['2112345678'],    // Missing leading 0
            ['0211234567'],    // Too short
            ['021123456789'],  // Too long
            ['021-1234-5678'], // Contains dashes
            ['021 1234 5678'], // Contains spaces
            ['021a1234567'],   // Contains letters
            ['121a1234567'],   // Area code doesn't start with 0
            ['0011234567'],    // Second digit in area code is 0
            [''],              // Empty string
            ['abcdefghijk'],   // Non-numeric
        ];
    }

    #[Test]
    #[DataProvider('validWithAreaCodeSeparatorProvider')]
    public function it_passes_for_valid_with_area_code_separator(string $value): void
    {
        $this->assertValidationPasses($value, $this->ruleAreaCodeSeparator);
    }

    #[Test]
    #[DataProvider('invalidWithAreaCodeSeparatorProvider')]
    public function it_fails_for_invalid_with_area_code_separator(string $value): void
    {
        $this->assertValidationFails($value, 'ir_phone_with_area_code', $this->ruleAreaCodeSeparator);
    }

    public static function validWithAreaCodeSeparatorProvider(): array
    {
        return [
            ['021-12345678'], // Tehran
            ['031-12345678'], // Isfahan
            ['051-12345678'], // Mashhad
            ['071-12345678'], // Shiraz
            ['011-12345678'], // Mazandaran
            ['041-12345678'], // Tabriz
            ['081-12345678'], // Hamedan
            ['061-12345678'], // Ahvaz
        ];
    }

    public static function invalidWithAreaCodeSeparatorProvider(): array
    {
        return [
            ['02112345678'],
            ['021-1234567'],
            ['021-123456789'],
            ['021 1234 5678'],
            ['021a1234567'],
            ['121a1234567'],
            ['0011234567'],
            ['abcdefghijk'],
        ];
    }

    #[Test]
    #[DataProvider('validProviderPersianNumbers')]
    public function it_passes_for_valid_persian_numbers(string $value): void
    {
        $this->assertValidationPasses($value, $this->rulePersianNumbers);
    }

    #[Test]
    #[DataProvider('invalidProviderPersianNumbers')]
    public function it_fails_for_invalid_persian_numbers(string $value): void
    {
        $this->assertValidationFails($value, 'ir_phone', $this->rulePersianNumbers);
    }

    public static function validProviderPersianNumbers(): array
    {
        return [
            ['۲۲۳۳۴۴۵۵'],
            ['۳۷۲۳۶۴۴۵'],
            ['۴۵۶۷۸۹۰۱'],
            ['۵۶۷۸۹۰۱۲'],
            ['۶۷۸۹۰۱۲۳'],
            ['۷۸۹۰۱۲۳۴'],
            ['۸۹۰۱۲۳۴۵'],
            ['۹۰۱۲۳۴۵۶'],
        ];
    }

    public static function invalidProviderPersianNumbers(): array
    {
        return [
            ['۲۱۱۲۳۴۵۶۷۸'],    // Missing leading 0
            ['۰۲۱۱۲۳۴۵۶۷'],    // Too short
            ['۰۲۱۱۲۳۴۵۶۷۸۹'],  // Too long
            ['۰۲۱-۱۲۳۴-۵۶۷۸'], // Contains dashes
            ['۰۲۱ ۱۲۳۴ ۵۶۷۸'], // Contains spaces
            ['۰۲۱a1234567'],  // Contains letters
            ['۱۲۱a1234567'],  // Area code doesn't start with 0
            ['۰۰۱۱۲۳۴۵۶۷'],    // Second digit in area code is 0
            [''],            // Empty string
            ['تست'],        // Non-numeric
        ];
    }
}
