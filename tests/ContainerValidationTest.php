<?php

namespace Sadegh19b\LaravelPersianValidation\Tests;

use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\Test;
use Sadegh19b\LaravelPersianValidation\PersianValidationServiceProvider;

class ContainerValidationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['persian-validation.register_rules' => true]);

        $this->app->register(PersianValidationServiceProvider::class);
    }

    #[Test]
    public function it_can_passes_basic_validation_rules_in_container()
    {
        // Test persian_alpha
        $validator = Validator::make(
            ['name' => 'علی'],
            ['name' => ['required', 'persian_alpha']]
        );
        $this->assertTrue($validator->passes(), 'Persian alpha validation should pass for valid input');

        // Test persian_alpha with invalid input
        $validator = Validator::make(
            ['name' => 'Ali123'],
            ['name' => ['required', 'persian_alpha']]
        );
        $this->assertFalse($validator->passes(), 'Persian alpha validation should fail for invalid input');
    }

    #[Test]
    public function it_can_passes_with_parameters_validation_rules_in_container()
    {
        // Test persian_date with different separators
        $validator = Validator::make(
            ['date' => '1402/12/29'],
            ['date' => ['required', 'persian_date']]
        );
        $this->assertTrue($validator->passes(), 'Persian date validation should pass with / separator');

        // Test persian_date with persian numbers and dash separator
        $validator = Validator::make(
            ['date' => '۱۴۰۲-۱۲-۲۹'],
            ['date' => ['required', 'persian_date:-,true']]
        );
        $this->assertTrue($validator->passes(), 'Persian date validation should pass with persian numbers and dash separator');

        // Test persian_date with invalid format
        $validator = Validator::make(
            ['date' => '1402-12-29'],
            ['date' => ['required', 'persian_date']]
        );
        $this->assertFalse($validator->passes(), 'Persian date validation should fail with invalid format');
    }
}
