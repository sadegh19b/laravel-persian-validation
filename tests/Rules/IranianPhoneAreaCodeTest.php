<?php

namespace Sadegh19b\LaravelPersianValidation\Tests\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sadegh19b\LaravelPersianValidation\Rules\IranianPhoneAreaCode;
use Sadegh19b\LaravelPersianValidation\Tests\TestCase;

class IranianPhoneAreaCodeTest extends TestCase
{
    protected ValidationRule $rule;
    protected ValidationRule $ruleWithPersianNumbers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rule = new IranianPhoneAreaCode();
        $this->ruleWithPersianNumbers = new IranianPhoneAreaCode(convertPersianNumbers: true);
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
        $this->assertValidationFails($value, 'ir_phone_area_code', $this->rule);
    }

    public static function validProvider(): array
    {
        return [
            ['021'], // Tehran
            ['031'], // Isfahan
            ['051'], // Mashhad
            ['071'], // Shiraz
            ['011'], // Mazandaran
            ['041'], // Tabriz
            ['081'], // Hamedan
            ['061'], // Ahvaz
            ['035'], // Yazd
            ['045'], // Ardabil
        ];
    }

    public static function invalidProvider(): array
    {
        return [
            ['21'],   // Too short
            ['0211'], // Too long
            ['121'],  // Doesn't start with 0
            ['001'],  // Second digit is 0
            ['0a1'],  // Contains letters
            ['02-1'], // Contains dash
            ['02 1'], // Contains space
            [''],     // Empty string
            ['abc'],  // Non-numeric
        ];
    }

    #[Test]
    #[DataProvider('validProviderPersianNumbers')]
    public function it_passes_for_valid_persian_numbers(string $value): void
    {
        $this->assertValidationPasses($value, $this->ruleWithPersianNumbers);
    }

    #[Test]
    #[DataProvider('invalidProviderPersianNumbers')]
    public function it_fails_for_invalid_persian_numbers(string $value): void
    {
        $this->assertValidationFails($value, 'ir_phone_area_code', $this->ruleWithPersianNumbers);
    }

    public static function validProviderPersianNumbers(): array
    {
        return [
            ['۰۲۱'],
            ['۰۳۱'],
            ['۰۵۱'],
            ['۰۷۱'],
            ['۰۱۱'],
            ['۰۴۱'],
            ['۰۸۱'],
            ['۰۶۱'],
            ['۰۳۵'],
            ['۰۴۵'],
        ];
    }

    public static function invalidProviderPersianNumbers(): array
    {
        return [
            ['۲۱'],   // Too short
            ['۰۲۱۱'], // Too long
            ['۱۲۱'],  // Doesn't start with 0
            ['۰۰۱'],  // Second digit is 0
            ['۰a1'],  // Contains letters
            ['۰۲-۱'], // Contains dash
            ['۰۲ ۱'], // Contains space
            [''],     // Empty string
            ['abc'],  // Non-numeric
        ];
    }
}
