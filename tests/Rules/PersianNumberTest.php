<?php

namespace Sadegh19b\LaravelPersianValidation\Tests\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sadegh19b\LaravelPersianValidation\Rules\PersianNumber;
use Sadegh19b\LaravelPersianValidation\Tests\TestCase;

class PersianNumberTest extends TestCase
{
    protected ValidationRule $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rule = new PersianNumber();
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
        $this->assertValidationFails($value, 'persian_num', $this->rule);
    }

    public static function validProvider(): array
    {
        return [
            ['۱۲۳۴۵۶۷۸۹۰'],
            ['١٢٣٤٥٦٧٨٩٠'],
        ];
    }

    public static function invalidProvider(): array
    {
        return [
            ['Test'],
            ['تست Test'],
            ['تست'],
            ['تست۱۲۳۴۵۶۷۸۹۰'],
            ['تست١٢٣٤٥٦٧٨٩٠'],
            ['تست123456789'],
            ['123456789'],
        ];
    }
}
