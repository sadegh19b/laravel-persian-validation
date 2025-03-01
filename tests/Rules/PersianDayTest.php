<?php

namespace Sadegh19b\LaravelPersianValidation\Tests\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\App;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sadegh19b\LaravelPersianValidation\Rules\PersianDay;
use Sadegh19b\LaravelPersianValidation\Tests\TestCase;

class PersianDayTest extends TestCase
{
    protected ValidationRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        App::setLocale('fa');

        $this->rule = new PersianDay();
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
        $this->assertValidationFails($value, 'persian_day', $this->rule);
    }

    public static function validProvider(): array
    {
        return [
            ['شنبه'],
            ['یکشنبه'],
            ['دوشنبه'],
            ['سه‌شنبه'],
            ['چهارشنبه'],
            ['پنج‌شنبه'],
            ['جمعه'],
        ];
    }

    public static function invalidProvider(): array
    {
        return [
            ['saturday'],
            ['invalid'],
            ['123'],
            ['شنبه1'],
            ['یک شنبه'], // Extra space
            ['دو شنبه'], // Extra space
            ['سه‌ شنبه'], // Extra space
            ['چهار شنبه'], // Extra space
        ];
    }
} 
