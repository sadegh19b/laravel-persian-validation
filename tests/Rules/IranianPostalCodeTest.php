<?php

namespace Sadegh19b\LaravelPersianValidation\Tests\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sadegh19b\LaravelPersianValidation\Tests\TestCase;
use Sadegh19b\LaravelPersianValidation\Rules\IranianPostalCode;

class IranianPostalCodeTest extends TestCase
{
    protected ValidationRule $rule;
    protected ValidationRule $ruleWithDashSeparator;
    protected ValidationRule $ruleWithSpaceSeparator;
    protected ValidationRule $ruleWithPersian;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rule = new IranianPostalCode();
        $this->ruleWithDashSeparator = new IranianPostalCode('-');
        $this->ruleWithSpaceSeparator = new IranianPostalCode('space');
        $this->ruleWithPersian = new IranianPostalCode(convertPersianNumbers: true);
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
        $this->assertValidationFails($value, 'ir_postal_code', $this->rule);
    }

    public static function validProvider(): array
    {
        return [
            ['1619735744'],
            ['3619735744'],
            ['4619735744'],
            ['5619735744'],
        ];
    }

    public static function invalidProvider(): array
    {
        return [
            // Invalid starting digits
            ['0619735744'],
            ['2619735744'],

            // Invalid formats
            ['161973574'],
            ['16197357440'],
            ['1619A35744'],
            ['161973-5744'],
            ['16197-357-44'],
            ['16197/35744'],
            ['11619735744'],

            // Invalid values
            ['abc'],
            ['123'],
            [''],
            [' '],
            ['16197'],
            ['16197-'],
            ['-35744'],
        ];
    }

    #[Test]
    #[DataProvider('validProviderWithDashSeparator')]
    public function it_passes_for_valid_data_with_dash_separator(string $value): void
    {
        $this->assertValidationPasses($value, $this->ruleWithDashSeparator);
    }

    #[Test]
    #[DataProvider('invalidProviderWithDashSeparator')]
    public function it_fails_for_invalid_data_with_dash_separator(string $value): void
    {
        $this->assertValidationFails($value, 'ir_postal_code_with_separator', $this->ruleWithDashSeparator);
    }

    public static function validProviderWithDashSeparator(): array
    {
        return [
            ['16197-35744'],
            ['36197-35744'],
            ['96197-35744'],
        ];
    }

    public static function invalidProviderWithDashSeparator(): array
    {
        return [
            ['1619735744'],
            ['16197 35744'],
            ['16197/35744'],
        ];
    }

    #[Test]
    #[DataProvider('validProviderWithSpace')]
    public function it_passes_for_valid_data_with_space_separator(string $value): void
    {
        $this->assertValidationPasses($value, $this->ruleWithSpaceSeparator);
    }

    #[Test]
    #[DataProvider('invalidProviderWithSpace')]
    public function it_fails_for_invalid_data_with_space_separator(string $value): void
    {
        $this->assertValidationFails($value, 'ir_postal_code_with_separator', $this->ruleWithSpaceSeparator);
    }

    public static function validProviderWithSpace(): array
    {
        return [
            ['16197 35744'],
            ['36197 35744'],
            ['96197 35744'],
        ];
    }

    public static function invalidProviderWithSpace(): array
    {
        return [
            ['1619735744'],
            ['16197-35744'],
            ['16197/35744'],
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
        $this->assertValidationFails($value, 'ir_postal_code', $this->ruleWithPersian);
    }

    public static function validProviderWithPersian(): array
    {
        return [
            ['۱۶۱۹۷۳۵۷۴۴'],
            ['۳۶۱۹۷۳۵۷۴۴'],
            ['۴۶۱۹۷۳۵۷۴۴'],
            ['۵۶۱۹۷۳۵۷۴۴'],
        ];
    }

    public static function invalidProviderWithPersian(): array
    {
        return [
            ['۰۶۱۹۷۳۵۷۴۴'],
            ['۲۶۱۹۷۳۵۷۴۴'],
            ['۱۶۱۹۷۳۵۷۴'],
            ['۱۶۱۹۷۳۵۷۴۴۰'],
            ['۱۶۱۹۷۳۵۷۴۴۴'],
            ['۱۶۱۹۷-۳۵۷۴۴'],
        ];
    }
}
