<?php

namespace Sadegh19b\LaravelPersianValidation\Tests\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sadegh19b\LaravelPersianValidation\Rules\PersianDateBetweenYear;
use Sadegh19b\LaravelPersianValidation\Tests\TestCase;

class PersianDateBetweenYearTest extends TestCase
{
    protected ValidationRule $rule;
    protected ValidationRule $rulePersian;
    protected ValidationRule $ruleSeparator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rule = new PersianDateBetweenYear(
            startYear: '1402',
            endYear: '1404'
        );
        $this->rulePersian = new PersianDateBetweenYear(
            startYear: '1402',
            endYear: '1404',
            convertPersianNumbers: true
        );
        $this->ruleSeparator = new PersianDateBetweenYear(
            startYear: '1402',
            endYear: '1404',
            separator: '-'
        );
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
        $this->assertValidationFails($value, 'persian_date_between_year', $this->rule);
    }

    public static function validProvider(): array
    {
        return [
            ['1403/03/03'],
            ['1403/12/30'], // Leap year
        ];
    }

    public static function invalidProvider(): array
    {
        return [
            ['1402/01/01'], // Equal the start year
            ['1404/01/01'], // Equal the end year
            ['1401/12/29'], // Before the start year
            ['1405/01/01'], // After the start year
            ['۱۴۰۳/۰۳/۰۳'], // Persian numbers not allowed in default mode
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
        $this->assertValidationFails($value, 'persian_date_between_year', $this->rulePersian);
    }

    public static function validPersianProvider(): array
    {
        return [
            ['۱۴۰۳/۰۳/۰۳'],
            ['1403/03/03'],
            ['۱۴۰۳/۰۱/۱۵'],
        ];
    }

    public static function invalidPersianProvider(): array
    {
        return [
            ['۱۴۰۱/۱۲/۲۹'], // Before the start year
            ['۱۴۰۵/۰۱/۰۱'], // After the end year
            ['not valid'],
            ['12345'],
            ['۱۴۰۳/۱۳/۰۱'], // Invalid month
            ['۱۴۰۳/۰۴/۳۲'], // Invalid day
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
        $this->assertValidationFails($value, 'persian_date_between_year', $this->ruleSeparator);
    }

    public static function validSeparatorProvider(): array
    {
        return [
            ['1403-03-03'],
            ['1403-01-15'],
            ['1403-12-29'],
        ];
    }

    public static function invalidSeparatorProvider(): array
    {
        return [
            ['1403/03/03'], // Wrong separator
            ['1401-12-29'], // Before the start year
            ['1405-01-01'], // After the end year
            ['۱۴۰۳-۰۳-۰۳'],
            ['not valid'],
            ['12345'],
            ['1403-13-01'], // Invalid month
            ['1403-04-32'], // Invalid day
        ];
    }

    #[Test]
    public function it_throws_exception_for_invalid_before_after_years(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $rule = new PersianDateBetweenYear(
            startYear: '14044', // Invalid year format (more than 4 digits)
            endYear: '1402'
        );

        $rule->validate('date', '1403/01/01', function() {});
    }

    #[Test]
    public function it_throws_exception_for_non_numeric_years(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $rule = new PersianDateBetweenYear(
            startYear: 'abcd', // Non-numeric year
            endYear: '1402'
        );

        $rule->validate('date', '1403/01/01', function() {});
    }
}
