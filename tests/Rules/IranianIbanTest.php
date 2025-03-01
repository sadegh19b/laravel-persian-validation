<?php

namespace Sadegh19b\LaravelPersianValidation\Tests\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sadegh19b\LaravelPersianValidation\Tests\TestCase;
use Sadegh19b\LaravelPersianValidation\Rules\IranianIban;

class IranianIbanTest extends TestCase
{
    protected ValidationRule $rule;
    protected ValidationRule $ruleWithoutPrefix;
    protected ValidationRule $ruleWithSeparator;
    protected ValidationRule $rulePersianNumbers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rule = new IranianIban();
        $this->ruleWithoutPrefix = new IranianIban(withPrefix: false);
        $this->ruleWithSeparator = new IranianIban(separator: 'space');
        $this->rulePersianNumbers = new IranianIban(convertPersianNumbers: true);
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
        $this->assertValidationFails($value, 'ir_iban', $this->rule);
    }

    public static function validProvider(): array
    {
        return [
            ['IR580540105180021273113007'], // Bank Pasargad
            ['IR062960000000100324200001'], // Bank Keshavarzi
        ];
    }

    public static function invalidProvider(): array
    {
        return [
            // Invalid length
            ['IR58012002000000481955937'],   // 25 characters
            ['IR5801200200000048195593781'], // 27 characters

            // Invalid format
            ['IR58012002000000481955937A'],   // Contains letter
            ['IR580120020000004819.55937'],   // With special character

            // Empty or invalid values
            [''],
            [' '],
            ['abc'],
            ['12345'],

            // Invalid country code
            ['US580120020000004819559378'],
            ['GB580120020000004819559378'],
            ['DE580120020000004819559378'],

            // Invalid checksum
            ['IR580120020000004819559379'],
            ['IR000000000000000000000000'],
            ['IR999999999999999999999999'],
        ];
    }

    #[Test]
    #[DataProvider('validProviderWithoutPrefix')]
    public function it_passes_for_valid_data_without_prefix(string $value): void
    {
        $this->assertValidationPasses($value, $this->ruleWithoutPrefix);
    }

    #[Test]
    #[DataProvider('invalidProviderWithoutPrefix')]
    public function it_fails_for_invalid_data_without_prefix(string $value): void
    {
        $this->assertValidationFails($value, 'ir_iban', $this->ruleWithoutPrefix);
    }

    public static function validProviderWithoutPrefix(): array
    {
        return [
            ['580540105180021273113007'], // Bank Pasargad
            ['062960000000100324200001'], // Bank Keshavarzi
        ];
    }

    public static function invalidProviderWithoutPrefix(): array
    {
        return [

            ['58012002000000481955937'],    // Invalid length
            ['5801200200000048195593781'],  // Invalid length
            ['58012002000000481955937A'],   // Contains letter
            ['580120020000004819.55937'],   // With special character
            [''],                           // Empty
            [' '],                          // Space
            ['abc'],                        // Invalid format
            ['12345'],                      // Invalid format
            ['US580120020000004819559378'], // Invalid country code
            ['580120020000004819559379'],   // Invalid checksum
            ['IR580540105180021273113007'], // With Prefix
        ];
    }

    #[Test]
    #[DataProvider('validProviderPersianNumbers')]
    public function it_passes_for_valid_data_with_persian_numbers(string $value): void
    {
        $this->assertValidationPasses($value, $this->rulePersianNumbers);
    }

    #[Test]
    #[DataProvider('invalidProviderPersianNumbers')]
    public function it_fails_for_invalid_data_with_persian_numbers(string $value): void
    {
        $this->assertValidationFails($value, 'ir_iban', $this->rulePersianNumbers);
    }

    public static function validProviderPersianNumbers(): array
    {
        return [
            ['IR۵۸۰۵۴۰۱۰۵۱۸۰۰۲۱۲۷۳۱۱۳۰۰۷'], // Bank Pasargad
            ['IR۰۶۲۹۶۰۰۰۰۰۰۰۱۰۰۳۲۴۲۰۰۰۰۱'], // Bank Keshavarzi
        ];
    }

    public static function invalidProviderPersianNumbers(): array
    {
        return [
            ['IR۵۸۰۱۲۰۰۲۰۰۰۰۰۰۴۸۱۹۵۵۹۳۷۹'],  // Invalid checksum
            ['IR۵۸۰۱۲۰۰۲۰۰۰۰۰۰۴۸۱۹۵۵۹۳۷'],   // Invalid length
            ['IR۵۸۰۱۲۰۰۲۰۰۰۰۰۰۴۸۱۹۵۵۹۳۷۸۱'], // Invalid length
            ['US۵۸۰۱۲۰۰۲۰۰۰۰۰۰۴۸۱۹۵۵۹۳۷۸'],  // Invalid country code
            ['۵۸۰۱۲۰۰۲۰۰۰۰۰۰۴۸۱۹۵۵۹۳۷۹'],    // Invalid checksum without prefix
        ];
    }

    #[Test]
    #[DataProvider('validProviderWithSeparator')]
    public function it_passes_for_valid_data_with_separator(string $value): void
    {
        $this->assertValidationPasses($value, $this->ruleWithSeparator);
    }

    #[Test]
    #[DataProvider('invalidProviderWithSeparator')]
    public function it_fails_for_invalid_data_with_separator(string $value): void
    {
        $this->assertValidationFails($value, 'ir_iban_with_separator', $this->ruleWithSeparator);
    }

    public static function validProviderWithSeparator(): array
    {
        return [
            ['IR58 0540 1051 8002 1273 1130 07'], // Bank Pasargad
            ['IR06 2960 0000 0010 0324 2000 01'], // Bank Keshavarzi
        ];
    }

    public static function invalidProviderWithSeparator(): array
    {
        return [
            ['IR58 0540 1051 8002 1273 1130'], // Invalid length
            ['IR58 0540 1051 8002 1273 1130 071'], // Invalid length
            ['IR58 0540 1051 8002 1273 1130 0A'], // Contains letter
            ['IR58.0540.1051.8002.1273.1130.07'], // Wrong separator
            ['IR580540105180021273113007'], // No separator
            ['IR58-0540-1051-8002-1273-1130-07'], // Wrong separator
            ['IR58 0540 1051 8002 1273 1130 79'], // Invalid checksum
        ];
    }
}
