<?php

namespace Sadegh19b\LaravelPersianValidation\Tests\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sadegh19b\LaravelPersianValidation\Rules\PersianDateBetween;
use Sadegh19b\LaravelPersianValidation\Tests\TestCase;

class PersianDateBetweenTest extends TestCase
{
    protected ValidationRule $rule;
    protected ValidationRule $rulePersian;
    protected ValidationRule $ruleSeparator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rule = new PersianDateBetween(
            startDate: '1402/01/01',
            endDate: '1404/01/01'
        );
        $this->rulePersian = new PersianDateBetween(
            startDate: '1402/01/01',
            endDate: '1404/01/01',
            convertPersianNumbers: true
        );
        $this->ruleSeparator = new PersianDateBetween(
            startDate: '1402-01-01',
            endDate: '1404-01-01',
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
        $this->assertValidationFails($value, 'persian_date_between', $this->rule);
    }

    public static function validProvider(): array
    {
        return [
            ['1403/03/03'],
            ['1402/12/29'],
        ];
    }

    public static function invalidProvider(): array
    {
        return [
            ['1402/01/01'], // Equal to start date
            ['1404/01/01'], // Equal to end date
            ['1401/12/29'], // Before the start date
            ['1404/01/02'], // After the end date
            ['۱۴۰۳/۰۳/۰۳'], // Persian numbers not allowed in default mode
            ['not valid'],
            ['12345'],
            ['1403/13/01'], // Invalid month
            ['1403/04/32'], // Invalid day
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
        $this->assertValidationFails($value, 'persian_date_between', $this->rulePersian);
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
            ['۱۴۰۱/۱۲/۲۹'], // Before the start date
            ['۱۴۰۴/۰۱/۰۲'], // After the end date
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
        $this->assertValidationFails($value, 'persian_date_between', $this->ruleSeparator);
    }

    public static function validSeparatorProvider(): array
    {
        return [
            ['1403-03-03'],
            ['1403-01-15'],
            ['1402-12-29'],
        ];
    }

    public static function invalidSeparatorProvider(): array
    {
        return [
            ['1403/03/03'], // Wrong separator
            ['1401-12-29'], // Before the start date
            ['1404-01-02'], // After the end date
            ['۱۴۰۳-۰۳-۰۳'],
            ['not valid'],
            ['12345'],
            ['1403-13-01'], // Invalid month
            ['1403-04-32'], // Invalid day
        ];
    }

    #[Test]
    public function it_throws_exception_for_invalid_before_after_dates(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $rule = new PersianDateBetween(
            startDate: '1404/13/01', // Invalid month
            endDate: '1402/01/01'
        );

        $rule->validate('date', '1403/01/01', function() {});
    }
}
