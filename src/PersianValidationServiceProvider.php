<?php

namespace Sadegh19b\LaravelPersianValidation;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\InvokableValidationRule;
use Sadegh19b\LaravelPersianValidation\Support\Enum;

class PersianValidationServiceProvider extends ServiceProvider
{
    protected array $rulesMap = [
        'persian_alpha'                      => 'PersianAlpha',
        'persian_alpha_num'                  => 'PersianAlphaNum',
        'persian_alpha_eng_num'              => 'PersianAlphaEngNum',
        'persian_num'                        => 'PersianNumber',
        'persian_not_accept'                 => 'PersianNotAccept',
        'persian_date'                       => 'PersianDate',
        'persian_date_between'               => 'PersianDateBetween',
        'persian_date_between_or_equal'      => 'PersianDateBetweenOrEqual',
        'persian_date_between_or_equal_year' => 'PersianDateBetweenOrEqualYear',
        'persian_date_between_year'          => 'PersianDateBetweenYear',
        'persian_day'                        => 'PersianDay',
        'persian_month'                      => 'PersianMonth',
        'ir_mobile'                          => 'IranianMobile',
        'ir_phone'                           => 'IranianPhone',
        'ir_phone_area_code'                 => 'IranianPhoneAreaCode',
        'ir_postal_code'                     => 'IranianPostalCode',
        'ir_bank_card_number'                => 'IranianBankCardNumber',
        'ir_iban'                            => 'IranianBankIban',
        'ir_national_id'                     => 'IranianNationalID',
        'ir_company_id'                      => 'IranianCompanyId',
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishLangFiles();
            $this->publishConfigFile();
        }

        $this->loadTranslationsFrom(__DIR__ . '/../lang/', Enum::NAMESPACE);
        $this->mergeConfigFrom(__DIR__ . '/../config/' . Enum::FILE_NAME . '.php', Enum::FILE_NAME);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Register rules in container
        if (config(Enum::FILE_NAME . '.register_rules', true)) {
            $this->registerRules();
        }
    }

    /**
     * Register validation rules.
     *
     * @ref https://laracasts.com/discuss/channels/laravel/validatorextend-custom-validationrule-laravel-10-example?page=1&replyId=936626
     *
     * @return void
     */
    protected function registerRules(): void
    {
        foreach ($this->rulesMap as $ruleName => $className) {
            $ruleClass = "Sadegh19b\\LaravelPersianValidation\\Rules\\{$className}";

            Validator::extend($ruleName, function ($attribute, $value, $parameters, $validator) use ($ruleClass) {
                $rule = InvokableValidationRule::make(new $ruleClass(...$parameters));

                $rule->setValidator($validator);
                $rule->setData($validator->getData());
                $result = $rule->passes($attribute, $value);

                if (! $result) {
                    $validator->setCustomMessages([
                        $attribute => Arr::first($rule->message()),
                    ]);
                }

                return $result;
            });
        }
    }

    /**
     * Publish config file.
     */
    protected function publishConfigFile(): void
    {
        $this->publishes([
            __DIR__ . '/../config/' . Enum::FILE_NAME . '.php' => config_path(Enum::FILE_NAME . '.php'),
        ], Enum::FILE_NAME . '-config');
    }

    /**
     * Publish language files.
     *
     * @return void
     */
    protected function publishLangFiles(): void
    {
        $this->publishes([
            __DIR__ . '/../lang' => lang_path(),
        ], Enum::FILE_NAME . '-lang');
    }
}
