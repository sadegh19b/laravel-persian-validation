<?php

namespace Sadegh19b\LaravelPersianValidation\Tests\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sadegh19b\LaravelPersianValidation\Tests\TestCase;
use Sadegh19b\LaravelPersianValidation\Rules\IranianBankCardNumber;

class IranianBankCardNumberTest extends TestCase
{
    protected ValidationRule $rule;
    protected ValidationRule $ruleWithDashSeparator;
    protected ValidationRule $ruleWithSpaceSeparator;
    protected ValidationRule $rulePersianNumbers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rule = new IranianBankCardNumber();
        $this->ruleWithDashSeparator = new IranianBankCardNumber(separator: '-');
        $this->ruleWithSpaceSeparator = new IranianBankCardNumber(separator: 'space');
        $this->rulePersianNumbers = new IranianBankCardNumber(convertPersianNumbers: true);
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
        $this->assertValidationFails($value,  'ir_bank_card_number', $this->rule);
    }

    public static function validProvider(): array
    {
        return [
            ['6037997599422129'], // Valid Melli card
            ['6274129005473742'], // Valid Eghtesad Novin card
            ['5022293633699644'], // Valid Pasargad card
            ['2071777125478548'], // Valid Saderat card
            ['9919753473757867'], // Valid Mellat card
        ];
    }

    public static function invalidProvider(): array
    {
        return [
            // Invalid length
            ['603799759943912'],   // 15 digits
            ['60379975994391280'], // 17 digits

            // Invalid format
            ['603799759943912a'],    // Contains letter
            ['6037-9975-9943-9128'], // With dashes
            ['6037 9975 9943 9128'], // With spaces
            ['603799759943912.'],    // With special character

            // Empty or invalid values
            [''],
            [' '],
            ['abc'],
            ['12345'],

            // Invalid starting digits
            ['0037997599439128'], // Starts with 0
            ['1037997599439128'], // Starts with 1
            ['3037997599439128'], // Starts with 3
            ['4037997599439128'], // Starts with 4
            ['7037997599439128'], // Starts with 7
            ['8037997599439128'], // Starts with 8

            // Invalid checksum
            ['6037997599439129'],
            ['5022291070000001'],
            ['0000000000000000'],
            ['1111111111111111'],
            ['2222222222222222'],
            ['3333333333333333'],
            ['4444444444444444'],
            ['5555555555555555'],
            ['6666666666666666'],
            ['7777777777777777'],
            ['8888888888888888'],
            ['9999999999999999'],
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
        $this->assertValidationFails($value, 'ir_bank_card_number_with_separator', $this->ruleWithDashSeparator);
    }

    public static function validProviderWithDashSeparator(): array
    {
        return [
            ['6037-9975-9942-2129'], // Valid Melli card
            ['6274-1290-0547-3742'], // Valid Eghtesad Novin card
            ['5022-2936-3369-9644'], // Valid Pasargad card
            ['2071-7771-2547-8548'], // Valid Saderat card
            ['9919-7534-7375-7867'], // Valid Mellat card
        ];
    }

    public static function invalidProviderWithDashSeparator(): array
    {
        return [
            ['6037997599439128'],     // No separators
            ['6037 9975 9943 9128'],  // Wrong separator
            ['6037/9975/9943/9128'],  // Wrong separator
            ['6037-9975-9943-912'],   // Invalid length
            ['6037-9975-9943-91280'], // Invalid length
            ['6037-9975-9943-9129'],  // Invalid checksum
            ['1037-9975-9943-9128'],  // Invalid starting digit
        ];
    }

    #[Test]
    #[DataProvider('validProviderWithSpaceSeparator')]
    public function it_passes_for_valid_data_with_space_separator(string $value): void
    {
        $this->assertValidationPasses($value, $this->ruleWithSpaceSeparator);
    }

    #[Test]
    #[DataProvider('invalidProviderWithSpaceSeparator')]
    public function it_fails_for_invalid_data_with_space_separator(string $value): void
    {
        $this->assertValidationFails($value, 'ir_bank_card_number_with_separator', $this->ruleWithSpaceSeparator);
    }

    public static function validProviderWithSpaceSeparator(): array
    {
        return [
            ['6037 9975 9942 2129'], // Valid Melli card
            ['6274 1290 0547 3742'], // Valid Eghtesad Novin card
            ['5022 2936 3369 9644'], // Valid Pasargad card
            ['2071 7771 2547 8548'], // Valid Saderat card
            ['9919 7534 7375 7867'], // Valid Mellat card
        ];
    }

    public static function invalidProviderWithSpaceSeparator(): array
    {
        return [
            ['6037997599439128'],     // No separators
            ['6037-9975-9943-9128'],  // Wrong separator
            ['6037/9975/9943/9128'],  // Wrong separator
            ['6037 9975 9943 912'],   // Invalid length
            ['6037 9975 9943 91280'], // Invalid length
            ['6037 9975 9943 9129'],  // Invalid checksum
            ['1037 9975 9943 9128'],  // Invalid starting digit
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
        $this->assertValidationFails($value, 'ir_bank_card_number', $this->rulePersianNumbers);
    }

    public static function validProviderPersianNumbers(): array
    {
        return [
            ['۶۰۳۷۹۹۷۵۹۹۴۲۲۱۲۹'], // Valid Melli card
            ['۶۲۷۴۱۲۹۰۰۵۴۷۳۷۴۲'], // Valid Eghtesad Novin card
            ['۵۰۲۲۲۹۳۶۳۳۶۹۹۶۴۴'], // Valid Pasargad card
            ['۲۰۷۱۷۷۷۱۲۵۴۷۸۵۴۸'], // Valid Saderat card
            ['۹۹۱۹۷۵۳۴۷۳۷۵۷۸۶۷'], // Valid Mellat card
        ];
    }

    public static function invalidProviderPersianNumbers(): array
    {
        return [
            ['۶۰۳۷۹۹۷۵۹۹۴۳۹۱۲۸'],     // No separators
            ['۶۰۳۷-۹۹۷۵-۹۹۴۳-۹۱۲۸'],  // Wrong separator
            ['۶۰۳۷/۹۹۷۵/۹۹۴۳/۹۱۲۸'],  // Wrong separator
            ['۶۰۳۷ ۹۹۷۵ ۹۹۴۳ ۹۱۲'],   // Invalid length
            ['۶۰۳۷ ۹۹۷۵ ۹۹۴۳ ۹۱۲۸۰'], // Invalid length
            ['۶۰۳۷۹۹۷۵۹۹۴۳۹۱۲۹'],  // Invalid checksum
            ['۱۰۳۷۹۹۷۵۹۹۴۳۹۱۲۸'],  // Invalid starting digit
        ];
    }
}
