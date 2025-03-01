<?php

namespace Sadegh19b\LaravelPersianValidation\Tests\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sadegh19b\LaravelPersianValidation\Rules\IranianMobile;
use Sadegh19b\LaravelPersianValidation\Tests\TestCase;

class IranianMobileTest extends TestCase
{
    protected ValidationRule $rule;
    protected ValidationRule $ruleZeroCode;
    protected ValidationRule $rulePlusCode;
    protected ValidationRule $ruleCode;
    protected ValidationRule $ruleZero;
    protected ValidationRule $ruleWithoutZero;
    protected ValidationRule $ruleAll;
    protected ValidationRule $ruleWithPersianNumbers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rule = new IranianMobile();
        $this->ruleZeroCode = new IranianMobile(format: 'zero_code');
        $this->rulePlusCode = new IranianMobile(format: 'plus_code');
        $this->ruleCode = new IranianMobile(format: 'code');
        $this->ruleZero  = new IranianMobile(format: 'zero');
        $this->ruleWithoutZero = new IranianMobile(format: 'normal');
        $this->ruleAll = new IranianMobile(format: 'all');
        $this->ruleWithPersianNumbers = new IranianMobile(convertPersianNumbers: true);
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
        $this->assertValidationFails($value, 'ir_mobile_with_country_code', $this->rule);
    }

    public static function validProvider(): array
    {
        return [
            ['00989123456789'],
            ['+989123456789'],
            ['989123456789'],
            ['09123456789'],
            ['9123456789'],
        ];
    }

    public static function invalidProvider(): array
    {
        return [
            ['0912345678'],    // Too short
            ['091234567890'],  // Too long
            ['09123456abc'],   // Contains letters
            ['0912 3456789'],  // Contains space
            ['0912-345-6789'], // Contains dash
            ['8912345678'],    // Starts with wrong digit
            [''],              // Empty string
            ['test'],          // Non-numeric
        ];
    }

    #[Test]
    #[DataProvider('validZeroCodeProvider')]
    public function it_passes_for_valid_zero_code_format(string $value): void
    {
        $this->assertValidationPasses($value, $this->ruleZeroCode);
    }

    #[Test]
    #[DataProvider('invalidZeroCodeProvider')]
    public function it_fails_for_invalid_zero_code_format(string $value): void
    {
        $this->assertValidationFails($value, 'ir_mobile_with_country_code', $this->ruleZeroCode);
    }

    public static function validZeroCodeProvider(): array
    {
        return [
            ['00989123456789'],
        ];
    }

    public static function invalidZeroCodeProvider(): array
    {
        return [
            ['+989123456789'],
            ['989123456789'],
            ['09123456789'],
            ['9123456789'],
        ];
    }

    #[Test]
    #[DataProvider('validPlusCodeProvider')]
    public function it_passes_for_valid_plus_code_format(string $value): void
    {
        $this->assertValidationPasses($value, $this->rulePlusCode);
    }

    #[Test]
    #[DataProvider('invalidPlusCodeProvider')]
    public function it_fails_for_invalid_plus_code_format(string $value): void
    {
        $this->assertValidationFails($value, 'ir_mobile_with_country_code', $this->rulePlusCode);
    }

    public static function validPlusCodeProvider(): array
    {
        return [
            ['+989123456789'],
        ];
    }

    public static function invalidPlusCodeProvider(): array
    {
        return [
            ['00989123456789'],
            ['989123456789'],
            ['09123456789'],
            ['9123456789'],
        ];
    }

    #[Test]
    #[DataProvider('validCodeProvider')]
    public function it_passes_for_valid_code_format(string $value): void
    {
        $this->assertValidationPasses($value, $this->ruleCode);
    }

    #[Test]
    #[DataProvider('invalidCodeProvider')]
    public function it_fails_for_invalid_code_format(string $value): void
    {
        $this->assertValidationFails($value, 'ir_mobile_with_country_code', $this->ruleCode);
    }

    public static function validCodeProvider(): array
    {
        return [
            ['989123456789'],
        ];
    }

    public static function invalidCodeProvider(): array
    {
        return [
            ['00989123456789'],
            ['+989123456789'],
            ['09123456789'],
            ['9123456789'],
        ];
    }

    #[Test]
    #[DataProvider('validZeroProvider')]
    public function it_passes_for_valid_zero_format(string $value): void
    {
        $this->assertValidationPasses($value, $this->ruleZero);
    }

    #[Test]
    #[DataProvider('invalidZeroProvider')]
    public function it_fails_for_invalid_zero_format(string $value): void
    {
        $this->assertValidationFails($value, 'ir_mobile', $this->ruleZero);
    }

    public static function validZeroProvider(): array
    {
        return [
            ['09123456789'],
        ];
    }

    public static function invalidZeroProvider(): array
    {
        return [
            ['00989123456789'],
            ['+989123456789'],
            ['989123456789'],
            ['9123456789'],
        ];
    }

    #[Test]
    #[DataProvider('validWithoutZeroProvider')]
    public function it_passes_for_valid_without_zero_format(string $value): void
    {
        $this->assertValidationPasses($value, $this->ruleWithoutZero);
    }

    #[Test]
    #[DataProvider('invalidWithoutZeroProvider')]
    public function it_fails_for_invalid_without_zero_format(string $value): void
    {
        $this->assertValidationFails($value, 'ir_mobile', $this->ruleWithoutZero);
    }

    public static function validWithoutZeroProvider(): array
    {
        return [
            ['9123456789'],
        ];
    }

    public static function invalidWithoutZeroProvider(): array
    {
        return [
            ['00989123456789'],
            ['+989123456789'],
            ['989123456789'],
            ['09123456789'],
        ];
    }

    #[Test]
    #[DataProvider('validAllProvider')]
    public function it_passes_for_valid_all_format(string $value): void
    {
        $this->assertValidationPasses($value, $this->ruleAll);
    }

    #[Test]
    #[DataProvider('invalidAllProvider')]
    public function it_fails_for_invalid_all_format(string $value): void
    {
        $this->assertValidationFails($value, 'ir_mobile_with_country_code', $this->ruleAll);
    }

    public static function validAllProvider(): array
    {
        return [
            ['00989123456789'],
            ['+989123456789'],
            ['989123456789'],
            ['09123456789'],
            ['9123456789'],
        ];
    }

    public static function invalidAllProvider(): array
    {
        return [
            ['0912345678'],    // Too short
            ['091234567890'],  // Too long
            ['09123456abc'],   // Contains letters
            ['0912 3456789'],  // Contains space
            ['0912-345-6789'], // Contains dash
            ['8912345678'],    // Starts with wrong digit
            [''],              // Empty string
            ['test'],          // Non-numeric
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
        $this->assertValidationFails($value, 'ir_mobile_with_country_code', $this->ruleWithPersianNumbers);
    }

    public static function validProviderPersianNumbers(): array
    {
        return [
            ['۰۰۹۸۹۱۲۳۴۵۶۷۸۹'],
            ['+۹۸۹۱۲۳۴۵۶۷۸۹'],
            ['۹۸۹۱۲۳۴۵۶۷۸۹'],
            ['۰۹۱۲۳۴۵۶۷۸۹'],
            ['۹۱۲۳۴۵۶۷۸۹'],
        ];
    }

    public static function invalidProviderPersianNumbers(): array
    {
        return [
            ['۰۹۱۲۳۴۵۶۷۸'],   // Too short
            ['۰۹۱۲۳۴۵۶۷۸۹۰'],  // Too long
            ['۰۹۱۲۳۴۵۶abc'],   // Contains letters
            ['۰۹۱۲ ۳۴۵۶۷۸۹'],  // Contains space
            ['۰۹۱۲-۳۴۵-۶۷۸۹'], // Contains dash
            ['۸۹۱۲۳۴۵۶۷۸'],    // Starts with wrong digit
            [''],             // Empty string
            ['تست'],          // Non-numeric
        ];
    }

    #[Test]
    #[DataProvider('validMixedProvider')]
    public function it_passes_for_valid_mixed_numbers(string $value): void
    {
        $rulePersian = new IranianMobile('all', true);
        $this->assertValidationPasses($value, $rulePersian);
    }

    public static function validMixedProvider(): array
    {
        return [
            ['۰۰۹۸9123456789'],
            ['+۹۸9123456789'],
            ['۹۸9123456789'],
            ['۰9123456789'],
            ['۹123456789'],
        ];
    }
}
