<?php

namespace Sadegh19b\LaravelPersianValidation\Tests;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Sadegh19b\LaravelPersianValidation\Support\Enum;
use PHPUnit\Framework\Attributes\Test;

class PersianValidationServiceProviderTest extends TestCase
{
    #[Test]
    public function it_can_publish_all_lang_folders()
    {
        $langs = File::directories(realpath(__DIR__.'/../lang'));

        foreach ($langs as $lang) {
            $lang = Str::after($lang, 'lang');
            $lang = Str::replace('\\', '', $lang);
            $lang = Str::replace('/', '', $lang);

            $this->artisan('vendor:publish', [
                '--tag' => Enum::FILE_NAME.'-lang',
            ]);

            $this->assertFileExists(lang_path($lang.'/'.Enum::FILE_NAME.'.php'));
        }
    }

    #[Test]
    public function it_can_publish_config_file()
    {
        $this->artisan('vendor:publish', [
            '--tag' => Enum::FILE_NAME.'-config',
        ]);

        $this->assertFileExists(config_path(Enum::FILE_NAME.'.php'));
    }
}
