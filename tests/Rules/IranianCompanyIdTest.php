<?php

namespace Sadegh19b\LaravelPersianValidation\Tests\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sadegh19b\LaravelPersianValidation\Tests\TestCase;
use Sadegh19b\LaravelPersianValidation\Rules\IranianCompanyId;

class IranianCompanyIdTest extends TestCase
{
    protected ValidationRule $rule;
    protected ValidationRule $ruleWithPersian;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rule = new IranianCompanyId();
        $this->ruleWithPersian = new IranianCompanyId(convertPersianNumbers: true);
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
        $this->assertValidationFails($value, 'ir_company_id', $this->rule);
    }

    public static function validProvider(): array
    {
        return [
            ['14007650912'],
            ['10101078604'],
            ['10380284790'],
        ];
    }

    public static function invalidProvider(): array
    {
        return [
            // Invalid length
            ['1234567890'],
            ['123456789012'],

            // Invalid format
            ['1234abc5678'],
            ['12345-67890'],
            ['12345678901a'],
            ['1400765091X'],

            // Invalid check digit
            ['10100971547'],

            // Invalid values
            ['abc'],
            ['123'],
            [''],
            [' '],
            ['00000000000'], // All zeros
            ['11111111111'], // All same digits
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
        $this->assertValidationFails($value, 'ir_company_id', $this->ruleWithPersian);
    }

    public static function validProviderWithPersian(): array
    {
        return [
            ['۱۴۰۰۷۶۵۰۹۱۲'],
            ['۱۰۱۰۱۰۷۸۶۰۴'],
            ['۱۰۳۸۰۲۸۴۷۹۰'],
        ];
    }

    public static function invalidProviderWithPersian(): array
    {
        return [
            ['۱۲۳۴۵۶۷۸۹۰'], // Invalid length
            ['۱۲۳۴۵۶۷۸۹۰۱۲'], // Invalid length
            ['۱۰۱۰۰۹۷۱۵۴۷'], // Invalid check digit
            ['۰۰۰۰۰۰۰۰۰۰۰'], // All zeros
            ['۱۲۳۴abc۵۶۷۸۹'], // Mixed with English characters
            ['۱۲۳۴۵-۶۷۸۹۰۱'], // Contains separator
        ];
    }
}
