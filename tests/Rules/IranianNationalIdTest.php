<?php

namespace Sadegh19b\LaravelPersianValidation\Tests\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sadegh19b\LaravelPersianValidation\Tests\TestCase;
use Sadegh19b\LaravelPersianValidation\Rules\IranianNationalId;

class IranianNationalIdTest extends TestCase
{
    protected ValidationRule $rule;
    protected ValidationRule $ruleWithPersian;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rule = new IranianNationalId();
        $this->ruleWithPersian = new IranianNationalId(convertPersianNumbers: true);
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
        $this->assertValidationFails($value, 'ir_national_id', $this->rule);
    }

    public static function validProvider(): array
    {
        return [
            ['0013542419'],
            ['0860170470'],
            ['3240175800'],
            ['3370075024'],
        ];
    }

    public static function invalidProvider(): array
    {
        return [
            // Invalid length
            ['12345678'],
            ['12345678901'],

            // Invalid format
            ['123abc4567'],
            ['12345-6789'],
            ['1234567890a'],

            // Invalid check digit
            ['1234567890'],
            ['0084575947'],
            ['0074862145'],

            // Invalid values
            ['abc'],
            ['123'],
            [''],
            [' '],
            ['0000000000'], // All zeros
            ['1111111111'], // Invalid check digit for repeated numbers
        ];
    }

    #[Test]
    #[DataProvider('validProviderWithPersian')]
    public function it_passes_for_valid_data_with_persian_numbers(string $value): void
    {
        $this->assertValidationPasses($value, $this->ruleWithPersian);
    }

    #[Test]
    #[DataProvider('invalidProviderWithPersian')]
    public function it_fails_for_invalid_data_with_persian_numbers(string $value): void
    {
        $this->assertValidationFails($value, 'ir_national_id', $this->ruleWithPersian);
    }

    public static function validProviderWithPersian(): array
    {
        return [
            ['۰۰۱۳۵۴۲۴۱۹'],
            ['۰۸۶۰۱۷۰۴۷۰'],
            ['۳۲۴۰۱۷۵۸۰۰'],
            ['۳۳۷۰۰۷۵۰۲۴'],
        ];
    }

    public static function invalidProviderWithPersian(): array
    {
        return [
            ['۱۲۳۴۵۶۷۸'], // Invalid length
            ['۱۲۳۴۵۶۷۸۹۰۱'], // Invalid length
            ['۱۲۳۴۵۶۷۸۹۰'], // Invalid check digit
            ['۰۰۰۰۰۰۰۰۰۰'], // All zeros
            ['۱۱۱۱۱۱۱۱۱۱'], // Invalid check digit for repeated numbers
            ['۱۲۳۴abc۵۶۷۸۹'], // Mixed with English characters
            ['تست'],
        ];
    }
}
