<?php

namespace Sadegh19b\LaravelPersianValidation\Tests\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sadegh19b\LaravelPersianValidation\Rules\PersianDate;
use Sadegh19b\LaravelPersianValidation\Tests\TestCase;

class PersianDateTest extends TestCase
{
    protected ValidationRule $rule;
    protected ValidationRule $rulePersian;
    protected ValidationRule $ruleSeparator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rule = new PersianDate();
        $this->rulePersian = new PersianDate(convertPersianNumbers: true);
        $this->ruleSeparator = new PersianDate(separator: '-');
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
        $this->assertValidationFails($value, 'persian_date', $this->rule);
    }

    public static function validProvider(): array
    {
        return [
            ['1403/03/03'],
            ['1403/3/3'],
            ['1402/12/29'],
            ['1403/12/30'], // Valid leap year
        ];
    }

    public static function invalidProvider(): array
    {
        return [
            ['۱۴۰۳/۰۳/۰۳'],
            ['not valid'],
            ['12345'],
            ['1403/13/01'], // Invalid month
            ['1403/04/32'], // Invalid day
            ['1402/12/30'], // Invalid leap year
        ];
    }

    #[Test]
    #[DataProvider('validPersianProvider')]
    public function it_passes_for_valid_data_persian_number(string $value): void
    {
        $this->assertValidationPasses($value, $this->rulePersian);
    }

    #[Test]
    #[DataProvider('invalidPersianProvider')]
    public function it_fails_for_invalid_data_persian_number(string $value): void
    {
        $this->assertValidationFails($value, 'persian_date', $this->rulePersian);
    }

    public static function validPersianProvider(): array
    {
        return [
            ['۱۴۰۳/۰۳/۰۳'],
            ['1403/03/03'],
        ];
    }

    public static function invalidPersianProvider(): array
    {
        return [
            ['not valid'],
            ['12345'],
        ];
    }

    #[Test]
    #[DataProvider('validSeparatorProvider')]
    public function it_passes_for_valid_data_separator(string $value): void
    {
        $this->assertValidationPasses($value, $this->ruleSeparator);
    }

    #[Test]
    #[DataProvider('invalidSeparatorProvider')]
    public function it_fails_for_invalid_data_separator(string $value): void
    {
        $this->assertValidationFails($value, 'persian_date', $this->ruleSeparator);
    }

    public static function validSeparatorProvider(): array
    {
        return [
            ['1403-03-03'],
        ];
    }

    public static function invalidSeparatorProvider(): array
    {
        return [
            ['1403/03/03'],
            ['۱۴۰۳/۰۳/۰۳'],
            ['۱۴۰۳-۰۳-۰۳'],
            ['not valid'],
            ['12345'],
        ];
    }
}
