<?php

namespace Sadegh19b\LaravelPersianValidation\Tests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Sadegh19b\LaravelPersianValidation\PersianValidationServiceProvider;
use Sadegh19b\LaravelPersianValidation\Support\Helper;

class TestCase extends BaseTestCase
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            PersianValidationServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function assertValidationPasses(string $value, ValidationRule $rule): void
    {
        $failCalled = false;

        $rule->validate('field', $value, function() use (&$failCalled) {
            $failCalled = true;

            return $this->createMock(PotentiallyTranslatedString::class);
        });

        $this->assertFalse($failCalled, "Validation should pass for '$value'");
    }

    protected function assertValidationFails(string $value, string $expectedMessageKey, ValidationRule $rule): void
    {
        $failMessage = null;

        $rule->validate('field', $value, function($message) use (&$failMessage) {
            $failMessage = $message;

            return $this->createMock(PotentiallyTranslatedString::class);
        });

        $this->assertEquals(Helper::translationKey($expectedMessageKey), $failMessage);
    }
}
