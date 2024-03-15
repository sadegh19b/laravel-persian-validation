<?php

namespace Sadegh19b\LaravelPersianValidation;

use Illuminate\Support\ServiceProvider;
use Validator;

/**
 * @author Sadegh Barzegar <sadegh19b@gmail.com>
 * @since September 18, 2019
 */
class PersianValidationServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    private $validatorsMap = [
        'persian_alpha'         => 'PersianAlpha',
        'persian_num'           => 'PersianNumber',
        'persian_alpha_num'     => 'PersianAlphaNumber',
        'persian_alpha_eng_num' => 'PersianAlphaEngNumber',
        'persian_not_accept'    => 'PersianNotAccept',
        'shamsi_date'           => 'ShamsiDate',
        'shamsi_date_between'   => 'ShamsiDateBetween',
        'ir_mobile'             => 'IranianMobile',
        'ir_phone'              => 'IranianPhone',
        'ir_phone_code'         => 'IranianPhoneAreaCode',
        'ir_phone_with_code'    => 'IranianPhoneWithAreaCode',
        'ir_postal_code'        => 'IranianPostalCode',
        'ir_bank_card_number'   => 'IranianBankCardNumber',
        'ir_sheba'              => 'IranianBankSheba',
        'ir_national_code'      => 'IranianNationalCode',
        'a_url'                 => 'CheckUrl',
        'a_domain'              => 'CheckDomain',
        'ir_company_id'         => 'IranianCompanyId',
    ];

    /**
     * Create Custom Persian Validation
     *
     * @return void
     */
    public function boot()
    {
        $vendorLangPath = $langLoaderPath = __DIR__.'/../lang/';
        $laravelVersion = explode('.', app()->version());
        // In Laravel 9+, lang files are in root path of app.
        $langPath = $laravelVersion[0] >= '9' ? base_path('lang/') : resource_path('lang/');
        $langFileName = 'persian-validation';
        $langNamespace = 'sbpValidation';

        // Publish language file to resources/lang/{AppLocale}/persian-validation.php
        $this->publishes([ $vendorLangPath => $langPath ]);

        if (count(glob($langPath . "*/{$langFileName}.php")) !== 0)
            $langLoaderPath = $langPath;

        $this->loadTranslationsFrom($langLoaderPath, $langNamespace);

        foreach($this->validatorsMap as $name => $method)
        {
            Validator::extend($name, PersianValidators::class . "@validate{$method}",
                              __("{$langNamespace}::{$langFileName}.{$name}"));
                              
            if (method_exists(PersianValidators::class, "replace{$method}")) {
                Validator::replacer($name, PersianValidators::class . "@replace{$method}");
            }
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
