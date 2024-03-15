<?php

use Sadegh19b\LaravelPersianValidation\PersianValidators;
use PHPUnit\Framework\TestCase;

/**
 * @author Sadegh Barzegar <sadegh19b@gmail.com>
 * @since Sep 18, 2019
 */
class PersianValidationTest extends TestCase
{
	/**
	 * @var null
	 */
    protected $attribute;

	/**
	 * @var string
	 */
    protected $value;

	/**
	 * @var array
	 */
    protected $parameters;

    /**
     * @var null
     */
    protected $validator;

	/**
     * @var object
	 */
	protected $persianValidator;

    protected function setUp(): void
    {
        $this->persianValidator = new PersianValidators();
    }

    /**
     * Unit test of persian alphabet
     *
     * @return void
     */
    public function testPersianAlpha()
    {
        $this->value = "Sadegh";
        $this->assertEquals(false, $this->persianValidator->validatePersianAlpha($this->attribute, $this->value, $this->parameters));

        $this->value = "صادق";
        $this->assertEquals(true, $this->persianValidator->validatePersianAlpha($this->attribute, $this->value, $this->parameters));

        $this->value =  "1111 صادق";
        $this->assertEquals(false, $this->persianValidator->validatePersianAlpha($this->attribute, $this->value, $this->parameters));

        $this->value = "صادق هستم";
        $this->assertEquals(true, $this->persianValidator->validatePersianAlpha($this->attribute, $this->value, $this->parameters));
	    
	    $this->value = "کُشته شًد، ِّۀٌُِّ،آۀَُِّژؤيژُّۀُ";
        $this->assertEquals(true, $this->persianValidator->validatePersianAlpha($this->attribute, $this->value, $this->parameters));
    }

    /**
     * Unit test of persian number
     *
     * @return void
     */
    public function testPersianNumber()
    {
        $this->value = "1234";
        $this->assertEquals(false, $this->persianValidator->validatePersianNumber($this->attribute, $this->value, $this->parameters));

        $this->value = "۱۲۳۴";
        $this->assertEquals(true, $this->persianValidator->validatePersianNumber($this->attribute, $this->value, $this->parameters));

        $this->value =  "۱۲۳123";
        $this->assertEquals(false, $this->persianValidator->validatePersianNumber($this->attribute, $this->value, $this->parameters));
    }

    /**
     * Unit test of persian alphabet and number
     *
     * @return void
     */
    public function testPersianAlphaNumber()
    {
        $this->value = "Sadegh1234";
        $this->assertEquals(false, $this->persianValidator->validatePersianAlphaNumber($this->attribute, $this->value, $this->parameters));

        $this->value = "1111صادق";
        $this->assertEquals(false, $this->persianValidator->validatePersianAlphaNumber($this->attribute, $this->value, $this->parameters));

        $this->value =  "1111صادق۱۲۳۴";
        $this->assertEquals(false, $this->persianValidator->validatePersianAlphaNumber($this->attribute, $this->value, $this->parameters));

        $this->value =  "صادق";
        $this->assertEquals(true, $this->persianValidator->validatePersianAlphaNumber($this->attribute, $this->value, $this->parameters));

        $this->value =  "۱۲۳۴";
        $this->assertEquals(true, $this->persianValidator->validatePersianAlphaNumber($this->attribute, $this->value, $this->parameters));

        $this->value =  "Sadegh۱۲۳۴صادق";
        $this->assertEquals(false, $this->persianValidator->validatePersianAlphaNumber($this->attribute, $this->value, $this->parameters));

        $this->value =  "۱۲۳۴ صادق";
        $this->assertEquals(true, $this->persianValidator->validatePersianAlphaNumber($this->attribute, $this->value, $this->parameters));
	
	    $this->value = "وَحِیُدّ‌الٍمٌاًسی";
	    $this->assertEquals(true, $this->persianValidator->validatePersianAlphaNumber($this->attribute, $this->value, $this->parameters));
    }

    /**
     * Unit test of persian alphabet and number with english number
     *
     * @return void
     */
    public function testPersianAlphaEngNumber()
    {
        $this->value = "Sadegh1234";
        $this->assertEquals(false, $this->persianValidator->validatePersianAlphaEngNumber($this->attribute, $this->value, $this->parameters));

        $this->value = "1111صادق";
        $this->assertEquals(true, $this->persianValidator->validatePersianAlphaEngNumber($this->attribute, $this->value, $this->parameters));

        $this->value =  "1111صادق۱۲۳۴";
        $this->assertEquals(true, $this->persianValidator->validatePersianAlphaEngNumber($this->attribute, $this->value, $this->parameters));

        $this->value =  "صادق";
        $this->assertEquals(true, $this->persianValidator->validatePersianAlphaEngNumber($this->attribute, $this->value, $this->parameters));

        $this->value =  "۱۲۳۴";
        $this->assertEquals(true, $this->persianValidator->validatePersianAlphaEngNumber($this->attribute, $this->value, $this->parameters));

        $this->value =  "1234";
        $this->assertEquals(true, $this->persianValidator->validatePersianAlphaEngNumber($this->attribute, $this->value, $this->parameters));

        $this->value =  "Sadegh۱۲۳۴صادق";
        $this->assertEquals(false, $this->persianValidator->validatePersianAlphaEngNumber($this->attribute, $this->value, $this->parameters));

        $this->value =  "۱۲۳۴ صادق";
        $this->assertEquals(true, $this->persianValidator->validatePersianAlphaEngNumber($this->attribute, $this->value, $this->parameters));

	    $this->value = "وَحِیُدّ‌الٍمٌاًسی";
	    $this->assertEquals(true, $this->persianValidator->validatePersianAlphaEngNumber($this->attribute, $this->value, $this->parameters));
    }

    /**
     * Unit test of shamsi date
     *
     * @return void
     */
    public function testShamsiDate()
    {
        $this->value = "1373/3/19";
        $this->assertEquals(true, $this->persianValidator->validateShamsiDate($this->attribute, $this->value, $this->parameters));

        $this->value = "1234";
        $this->assertEquals(false, $this->persianValidator->validateShamsiDate($this->attribute, $this->value, $this->parameters));
    }

    /**
     * Unit test of shamsi date between years
     *
     * @return void
     */
    public function testShamsiDateBetween()
    {
        $this->value = "1373/3/19";
        $this->parameters = [1300, 1400];
        $this->assertEquals(true, $this->persianValidator->validateShamsiDateBetween($this->attribute, $this->value, $this->parameters));

        $this->value = "1401/1/1";
        $this->parameters = [1300, 1400];
        $this->assertEquals(false, $this->persianValidator->validateShamsiDateBetween($this->attribute, $this->value, $this->parameters));

        $this->value = "1234";
        $this->assertEquals(false, $this->persianValidator->validateShamsiDate($this->attribute, $this->value, $this->parameters));
    }

    /**
     * Unit test of iranian mobile number
     *
     * @return void
     */
    public function testIranMobile()
    {
        $this->value = "+989355214655";
        $this->assertEquals(true, $this->persianValidator->validateIranianMobile($this->attribute, $this->value, $this->parameters));

        $this->value = "989355214655";
        $this->assertEquals(true, $this->persianValidator->validateIranianMobile($this->attribute, $this->value, $this->parameters));

        $this->value = "00989355214655";
        $this->assertEquals(true, $this->persianValidator->validateIranianMobile($this->attribute, $this->value, $this->parameters));

        $this->value = "09355214655";
        $this->assertEquals(true, $this->persianValidator->validateIranianMobile($this->attribute, $this->value, $this->parameters));

        $this->value = "09901464762";
        $this->assertEquals(true, $this->persianValidator->validateIranianMobile($this->attribute, $this->value, $this->parameters));

        $this->value = "9901464762";
        $this->assertEquals(true, $this->persianValidator->validateIranianMobile($this->attribute, $this->value, $this->parameters));
    }

    /**
     * Unit test of iranian bank sheba number
     *
     * @return void
     */
    public function testIranianBankSheba()
    {
        $this->value = "IR062960000000100324200001";
        $this->assertEquals(true, $this->persianValidator->validateIranianBankSheba($this->attribute, $this->value, $this->parameters));

        $this->value = "IR06296000000010032420000";
        $this->assertEquals(false, $this->persianValidator->validateIranianBankSheba($this->attribute, $this->value, $this->parameters));

        $this->value = "00062960000000100324200001";
        $this->assertEquals(false, $this->persianValidator->validateIranianBankSheba($this->attribute, $this->value, $this->parameters));
    }

    /**
     * Unit test of iran national number (code melli)
     *
     * @return void
     */
    public function testIranNationalCode()
    {
        $this->value = "0013542419";
        $this->assertEquals(true, $this->persianValidator->validateIranianNationalCode($this->attribute, $this->value, $this->parameters));

        $this->value = "3240175800";
        $this->assertEquals(true, $this->persianValidator->validateIranianNationalCode($this->attribute, $this->value, $this->parameters));

        $this->value = "3240164175";
        $this->assertEquals(true, $this->persianValidator->validateIranianNationalCode($this->attribute, $this->value, $this->parameters));

        $this->value = "3370075024";
        $this->assertEquals(true, $this->persianValidator->validateIranianNationalCode($this->attribute, $this->value, $this->parameters));

        $this->value = "0010532129";
        $this->assertEquals(true, $this->persianValidator->validateIranianNationalCode($this->attribute, $this->value, $this->parameters));

        $this->value = "0860170470";
        $this->assertEquals(true, $this->persianValidator->validateIranianNationalCode($this->attribute, $this->value, $this->parameters));

        $this->value = "324011122";
        $this->assertEquals(false, $this->persianValidator->validateIranianNationalCode($this->attribute, $this->value, $this->parameters));

        $this->value = "3213213";
        $this->assertEquals(false, $this->persianValidator->validateIranianNationalCode($this->attribute, $this->value, $this->parameters));

        $this->value = "0000000000";
        $this->assertEquals(false, $this->persianValidator->validateIranianNationalCode($this->attribute, $this->value, $this->parameters));

        $this->value = "1111111111";
        $this->assertEquals(false, $this->persianValidator->validateIranianNationalCode($this->attribute, $this->value, $this->parameters));

        $this->value = "2222222222";
        $this->assertEquals(false, $this->persianValidator->validateIranianNationalCode($this->attribute, $this->value, $this->parameters));

        $this->value = "3333333333";
        $this->assertEquals(false, $this->persianValidator->validateIranianNationalCode($this->attribute, $this->value, $this->parameters));

        $this->value = "4444444444";
        $this->assertEquals(false, $this->persianValidator->validateIranianNationalCode($this->attribute, $this->value, $this->parameters));

        $this->value = "5555555555";
        $this->assertEquals(false, $this->persianValidator->validateIranianNationalCode($this->attribute, $this->value, $this->parameters));

        $this->value = "6666666666";
        $this->assertEquals(false, $this->persianValidator->validateIranianNationalCode($this->attribute, $this->value, $this->parameters));

        $this->value = "7777777777";
        $this->assertEquals(false, $this->persianValidator->validateIranianNationalCode($this->attribute, $this->value, $this->parameters));

        $this->value = "8888888888";
        $this->assertEquals(false, $this->persianValidator->validateIranianNationalCode($this->attribute, $this->value, $this->parameters));

        $this->value = "9999999999";
        $this->assertEquals(false, $this->persianValidator->validateIranianNationalCode($this->attribute, $this->value, $this->parameters));
    }

    /**
     * Unit test of not accept persian alphabet and number
     *
     * @return void
     */
    public function testPersianNotAccept()
    {
      $this->value = "صادق۱۲۳۴";
      $this->assertEquals(false, $this->persianValidator->validatePersianNotAccept($this->attribute, $this->value, $this->parameters));

      $this->value = "sadegh";
      $this->assertEquals(true, $this->persianValidator->validatePersianNotAccept($this->attribute, $this->value, $this->parameters));

      $this->value = "sadeghصادق۱۲۳۴";
      $this->assertEquals(false, $this->persianValidator->validatePersianNotAccept($this->attribute, $this->value, $this->parameters));

      $this->value = "sadegh3289834(!!!%$$(@_)_)_";
      $this->assertEquals(true, $this->persianValidator->validatePersianNotAccept($this->attribute, $this->value, $this->parameters));

      $this->value = 1213131313131;
      $this->assertEquals(false, $this->persianValidator->validatePersianNotAccept($this->attribute, $this->value, $this->parameters));

      $this->value = ["Sadegh"];
      $this->assertEquals(false, $this->persianValidator->validatePersianNotAccept($this->attribute, $this->value, $this->parameters));
    }

    /**
     * Unit test of url
     *
     * @return void
     */
    public function testUrl()
    {
        $this->value = "http://google.com";
        $this->assertEquals(true, $this->persianValidator->validateCheckUrl($this->attribute, $this->value, $this->parameters));

        $this->value = "http/df;fdl";
        $this->assertEquals(false, $this->persianValidator->validateCheckUrl($this->attribute, $this->value, $this->parameters));
    }

    /**
     * Unit test of domain
     *
     * @return void
     */
    public function testDomain()
    {
        $this->value = "www.google.com";
        $this->assertEquals(true, $this->persianValidator->validateCheckDomain($this->attribute, $this->value, $this->parameters));

        $this->value = "xn--pgba0a.com";
        $this->assertEquals(true, $this->persianValidator->validateCheckDomain($this->attribute, $this->value, $this->parameters));

        $this->value = "deviner.ir";
        $this->assertEquals(true, $this->persianValidator->validateCheckDomain($this->attribute, $this->value, $this->parameters));

        $this->value = "dshgf---df.w";
        $this->assertEquals(false, $this->persianValidator->validateCheckDomain($this->attribute, $this->value, $this->parameters));

        $this->value = "www.go#ogle.com";
        $this->assertEquals(false, $this->persianValidator->validateCheckDomain($this->attribute, $this->value, $this->parameters));
        
        $this->value = "www.google.co,m";
        $this->assertEquals(false, $this->persianValidator->validateCheckDomain($this->attribute, $this->value, $this->parameters));

    }

    /**
     * Unit test of iran phone number
     *
     * @return void
     */
    public function testIranPhone()
    {
        $this->value = '07236445';
        $this->assertEquals(false, $this->persianValidator->validateIranianPhone($this->attribute, $this->value, $this->parameters));

        $this->value = '7236445';
        $this->assertEquals(false, $this->persianValidator->validateIranianPhone($this->attribute, $this->value, $this->parameters));

		$this->value = '17236445';
		$this->assertEquals(false, $this->persianValidator->validateIranianPhone($this->attribute, $this->value, $this->parameters));

        $this->value = '37236445';
        $this->assertEquals(true, $this->persianValidator->validateIranianPhone($this->attribute, $this->value, $this->parameters));
    }

    /**
     * Unit test of iran phone area code number
     *
     * @return void
     */
    public function testIranPhoneAreaCode()
    {
        $this->value = '5465498';
        $this->assertEquals(false, $this->persianValidator->validateIranianPhoneAreaCode($this->attribute, $this->value, $this->parameters));

        $this->value = '77';
        $this->assertEquals(false, $this->persianValidator->validateIranianPhoneAreaCode($this->attribute, $this->value, $this->parameters));

        $this->value = '01';
        $this->assertEquals(false, $this->persianValidator->validateIranianPhoneAreaCode($this->attribute, $this->value, $this->parameters));

        $this->value = '077';
        $this->assertEquals(true, $this->persianValidator->validateIranianPhoneAreaCode($this->attribute, $this->value, $this->parameters));

        $this->value = '021';
        $this->assertEquals(true, $this->persianValidator->validateIranianPhoneAreaCode($this->attribute, $this->value, $this->parameters));
    }

    /**
     * Unit test of iran phone number with area code
     *
     * @return void
     */
    public function testIranPhoneWithAreaCode()
    {
        $this->value = '07236445';
        $this->assertEquals(false, $this->persianValidator->validateIranianPhoneWithAreaCode($this->attribute, $this->value, $this->parameters));

        $this->value = '7236445';
        $this->assertEquals(false, $this->persianValidator->validateIranianPhoneWithAreaCode($this->attribute, $this->value, $this->parameters));

        $this->value = '17236445';
        $this->assertEquals(false, $this->persianValidator->validateIranianPhoneWithAreaCode($this->attribute, $this->value, $this->parameters));

        $this->value = '37236445';
        $this->assertEquals(false, $this->persianValidator->validateIranianPhoneWithAreaCode($this->attribute, $this->value, $this->parameters));

        $this->value = '02137236445';
        $this->assertEquals(true, $this->persianValidator->validateIranianPhoneWithAreaCode($this->attribute, $this->value, $this->parameters));
    }

    /**
     * Unit test of iranian bank payment card number
     *
     * @return void
     */
    public function testIranBankCardNumber()
    {
        $this->value = '6274-1290-0547-3742';
        $this->assertEquals(false, $this->persianValidator->validateIranianBankCardNumber($this->attribute, $this->value, $this->parameters));

        $this->value = '6274129107473842';
        $this->assertEquals(false, $this->persianValidator->validateIranianBankCardNumber($this->attribute, $this->value, $this->parameters));

        $this->value = '6274 1290 0547 3742';
        $this->assertEquals(false, $this->persianValidator->validateIranianBankCardNumber($this->attribute, $this->value, $this->parameters));

        $this->value = '627412900742';
        $this->assertEquals(false, $this->persianValidator->validateIranianBankCardNumber($this->attribute, $this->value, $this->parameters));

        $this->value = '62741290054737423252';
        $this->assertEquals(false, $this->persianValidator->validateIranianBankCardNumber($this->attribute, $this->value, $this->parameters));

        $this->value = '6274129005473742';
        $this->assertEquals(true, $this->persianValidator->validateIranianBankCardNumber($this->attribute, $this->value, $this->parameters));
    }

    /**
     * Unit test of iran postal code
     *
     * @return void
     */
    public function testIranPostalCode()
    {
        $this->value = "1619735744";
        $this->assertEquals(true, $this->persianValidator->validateIranianPostalCode($this->attribute, $this->value, $this->parameters));

        $this->value = "16197-35744";
        $this->assertEquals(true, $this->persianValidator->validateIranianPostalCode($this->attribute, $this->value, $this->parameters));

        $this->value = "116197-35744";
        $this->assertEquals(false, $this->persianValidator->validateIranianPostalCode($this->attribute, $this->value, $this->parameters));

        $this->value = "11619735744";
        $this->assertEquals(false, $this->persianValidator->validateIranianPostalCode($this->attribute, $this->value, $this->parameters));
    }

    /**
     * Unit test for Iranian company ID validation.
     *
     * @return void
     */
    public function testIranCompanyID()
    {
        // Valid Iranian company ID
        $this->assertTrue($this->persianValidator->validateIranianCompanyId($this->attribute, "14007650912", $this->parameters));

        // Invalid Iranian company ID: incorrect length
        $this->assertFalse($this->persianValidator->validateIranianCompanyId($this->attribute, "1234567890", $this->parameters));

        // Invalid Iranian company ID: contains non-numeric characters
        $this->assertFalse($this->persianValidator->validateIranianCompanyId($this->attribute, "1400765091a", $this->parameters));

        // Invalid Iranian company ID: repetitive numbers
        $this->assertFalse($this->persianValidator->validateIranianCompanyId($this->attribute, "11111111111", $this->parameters));

        // Invalid Iranian company ID: checksum mismatch
        $this->assertFalse($this->persianValidator->validateIranianCompanyId($this->attribute, "14007650911", $this->parameters));

        // Invalid Iranian company ID: unexpected decimal number
        $this->assertFalse($this->persianValidator->validateIranianCompanyId($this->attribute, "1400765091X", $this->parameters));
    }
}
