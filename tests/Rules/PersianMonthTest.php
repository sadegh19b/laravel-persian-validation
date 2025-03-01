<?php

namespace Sadegh19b\LaravelPersianValidation\Tests\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\App;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sadegh19b\LaravelPersianValidation\Rules\PersianMonth;
use Sadegh19b\LaravelPersianValidation\Tests\TestCase;

class PersianMonthTest extends TestCase
{
    protected ValidationRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        App::setLocale('fa');

        $this->rule = new PersianMonth();
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
        $this->assertValidationFails($value, 'persian_month', $this->rule);
    }

    public static function validProvider(): array
    {
        return [
            ['فروردین'],
            ['اردیبهشت'],
            ['خرداد'],
            ['تیر'],
            ['مرداد'],
            ['شهریور'],
            ['مهر'],
            ['آبان'],
            ['آذر'],
            ['دی'],
            ['بهمن'],
            ['اسفند'],
        ];
    }

    public static function invalidProvider(): array
    {
        return [
            ['january'],
            ['invalid'],
            ['123'],
            ['فروردین1'],
            ['اردی بهشت'], // Extra space
            ['شهری ور'], // Extra space
            ['آ بان'], // Extra space
            ['دی ماه'], // Extra text
            ['ماه بهمن'], // Extra text
            ['اسفند ماه'], // Extra text
        ];
    }
} 
