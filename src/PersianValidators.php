<?php

namespace Sadegh19b\LaravelPersianValidation;

/**
 * @author Sadegh Barzegar <sadegh19b@gmail.com>
 * @since Sep 18, 2019
 */
class PersianValidators
{

    /**
     * Validate persian alphabet and space.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validatePersianAlpha($attribute, $value, $parameters)
    {
        return preg_match("/^[\x{600}-\x{6FF}\x{200c}\x{064b}\x{064d}\x{064c}\x{064e}\x{064f}\x{0650}\x{0651}\s]+$/u", $value);
    }

    /**
     * Validate persian number.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validatePersianNumber($attribute, $value, $parameters)
    {
        return preg_match('/^[\x{6F0}-\x{6F9}]+$/u', $value);
    }

    /**
     * Validate persian alphabet, number and space.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validatePersianAlphaNumber($attribute, $value, $parameters)
    {
        return preg_match('/^[\x{600}-\x{6FF}\x{200c}\x{064b}\x{064d}\x{064c}\x{064e}\x{064f}\x{0650}\x{0651}\s]+$/u', $value);
    }


    /**
     * Validate persian alphabet, persian number, english number and space.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validatePersianAlphaEngNumber($attribute, $value, $parameters)
    {
        return preg_match('/^[\x{600}-\x{6FF}\x{200c}\x{064b}\x{064d}\x{064c}\x{064e}\x{064f}\x{0650}\x{0651}\0-9\s]+$/u', $value);
    }

    /**
     * Validate string that is not contain persian alphabet and number.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validatePersianNotAccept($attribute, $value, $parameters)
    {
        if (is_string($value)) {
            return !preg_match("/[\x{600}-\x{6FF}]/u", $value);
        }

        return false;
    }

    /**
     * Validate shamsi (jalali) date
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateShamsiDate($attribute, $value, $parameters)
    {
        if (isset($parameters[0]) && $parameters[0] == 'persian') {
            $value = $this->faToEnNumbers($value);
        }

        $jdate = preg_split('/(\-|\/)/', $value);
        return (count($jdate) === 3 && $this->isValidjDate($jdate[0], $jdate[1], $jdate[2]));
    }

    /**
     * Validate shamsi (jalali) date between years
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateShamsiDateBetween($attribute, $value, $parameters)
    {
        if (!isset($parameters[0]) && !isset($parameters[1])) {
            return false;
        }
        
        if (isset($parameters[2]) && $parameters[2] == 'persian') {
            $value = $this->faToEnNumbers($value);
        }

        $jdate = preg_split('/(\-|\/)/', $value);
        return $this->validateShamsiDate($attribute, $value, $parameters) && ($parameters[0] <= $jdate[0] && $parameters[1] >= $jdate[0]);
    }

    /**
     * Replace validate message for ShamsiDateBetween
     *
     * @param $message
     * @param $attribute
     * @param $rule
     * @param $parameters
     * @return bool
     */
    public function replaceShamsiDateBetween($message, $attribute, $rule, $parameters)
    {
        return str_replace([':afterDate', ':beforeDate'], [$parameters[0], $parameters[1]], $message);
    }

    /**
     * Validate a jalali date (jalali equivalent of php checkdate() function)
     * Refer to: https://github.com/hekmatinasser/verta (v1.11.5) => Comparison Trait => isValidDate function
     *
     * @param int $month
     * @param int $day
     * @param int $year
     * @return bool
     */
    private function isValidjDate($year, $month, $day) {
        if($year < 0 || $year > 32766) {
            return false;
        }
        if($month < 1 || $month > 12) {
            return false;
        }

        $daysMonthJalali = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];
        $dayLastMonthJalali = in_array(($year % 33) , [1 , 5 , 9 , 13 ,17 , 22 , 26 , 30]) && ($month == 12) ? 30 : $daysMonthJalali[intval($month)-1];
        if($day < 1 || $day > $dayLastMonthJalali) {
            return false;
        }

        return true;
    }

    /**
     * Convert persian numbers to english numbers
     *
     * @param string $string
     * @return string
     */
    private function faToEnNumbers($string)
    {
        $fa_num = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $en_num = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        return str_replace($fa_num, $en_num, $string);
    }

    /**
     * Validate iranian mobile number.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateIranianMobile($attribute, $value, $parameters)
    {
        $paramsPatternMap = [
            'zero_code'    => '/^(00989){1}[0-9]{9}+$/',
            'plus'         => '/^(\+989){1}[0-9]{9}+$/',
            'code'         => '/^(989){1}[0-9]{9}+$/',
            'zero'         => '/^(09){1}[0-9]{9}+$/',
            'without_zero' => '/^(9){1}[0-9]{9}+$/',
        ];

        if (isset($parameters[0]) && in_array($parameters[0], array_keys($paramsPatternMap))) {
            return preg_match($paramsPatternMap[$parameters[0]], $value);
        }

        return (preg_match('/^(((98)|(\+98)|(0098)|0)(9){1}[0-9]{9})+$/', $value) || preg_match('/^(9){1}[0-9]{9}+$/', $value))? true : false;
    }

    /**
     * Validate iran phone number.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateIranianPhone($attribute, $value, $parameters)
    {
        return preg_match('/^[2-9][0-9]{7}+$/', $value);
    }

    /**
     * Validate iran phone area code number.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateIranianPhoneAreaCode($attribute, $value, $parameters)
    {
        return preg_match('/^(0[1-9]{2})+$/', $value);
    }

    /**
     * Validate iran phone number with area code.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return boolean
     */
    public function validateIranianPhoneWithAreaCode($attribute, $value, $parameters)
    {
        return preg_match('/^(0[1-9]{2})[2-9][0-9]{7}+$/', $value) ;
    }

    /**
     * Validate Iran postal code format.
     * New Ref: https://blog.tapin.ir/%D9%85%D8%B9%D8%B1%D9%81%DB%8C-%D8%B3%D8%A7%D8%AE%D8%AA%D8%A7%D8%B1-%DA%A9%D8%AF-%D8%B1%D9%87%DA%AF%DB%8C%D8%B1%DB%8C-%D9%88-%DA%A9%D8%AF%D9%BE%D8%B3%D8%AA%DB%8C/
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateIranianPostalCode($attribute, $value, $parameters)
    {
        $paramsPatternMap = [
            'seprate'         => '/\b(?!(\d)\1{3})[13-9]{5}-[0-9]{5}\b/',
            'without_seprate' => '/\b(?!(\d)\1{3})[13-9]{5}[0-9]{5}\b/',
        ];

        if (isset($parameters[0]) && in_array($parameters[0], array_keys($paramsPatternMap))) {
            return preg_match($paramsPatternMap[$parameters[0]], $value);
        }

        return preg_match("/\b(?!(\d)\1{3})[13-9]{5}-?[0-9]{5}\b/", $value);
    }

    /**
     * Validate iranian bank payment card number validation.
     * depending on 'http://www.aliarash.com/article/creditcart/credit-debit-cart.htm' article.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    function validateIranianBankCardNumber($attribute, $value, $parameters)
    {
        if (isset($parameters[0]) && $parameters[0] == 'seprate') {
            if (!preg_match('/^\d{4}-\d{4}-\d{4}-\d{4}$/', $value)) {
                return false;
            }
            $value = str_replace('-', '', $value);
        }

        if (isset($parameters[0]) && $parameters[0] == 'space') {
            if (!preg_match('/^\d{4}\s\d{4}\s\d{4}\s\d{4}$/', $value)) {
                return false;
            }
            $value = str_replace(' ', '', $value);
        }

        if (!preg_match('/^\d{16}$/', $value)) {
            return false;
        }

        $sum = 0;

        for ($position = 1; $position <= 16; $position++){
            $temp = $value[$position - 1];
            $temp = $position % 2 === 0 ? $temp : $temp * 2;
            $temp = $temp > 9 ? $temp - 9 : $temp;

            $sum += $temp;
        }

        return ($sum % 10 === 0);
    }

    /**
     * Validate iranian bank sheba number (IBAN).
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateIranianBankSheba($attribute, $value, $parameters)
    {
        $value = preg_replace('/[\W_]+/', '', strtoupper($value));

        if (! preg_match('/^[A-Z]{2}\d{2}[A-Z0-9]{0,30}$/', $value)) {
            return false;
        }

        $ibanReplaceValues = array_combine(range('A', 'Z'), range(10, 35));
        $tmpIBAN = substr($value, 4) . substr($value, 0, 4);
        $tmpIBAN = strtr($tmpIBAN, $ibanReplaceValues);

        $tmpValue = 0;
        foreach (str_split($tmpIBAN) as $char) {
            $tmpValue = ($tmpValue * 10 + (int)$char) % 97;
        }

        return $tmpValue == 1;
    }

   /**
    * Validate iranian national code number (Code Melli)
    *
    * @param $attribute
    * @param $value
    * @param $parameters
    * @return bool
    */
    public function validateIranianNationalCode($attribute, $value, $parameters)
    {
        if (!preg_match('/^\d{8,10}$/', $value) || preg_match('/^(.)\1{9}$/', $value)) {
            return false;
        }

        $value = str_pad($value, 10, '0', STR_PAD_LEFT);

        $sub = 0;
        for ($i = 0; $i < 9; $i++) {
            $sub += $value[$i] * (10 - $i);
        }

        $control = $sub % 11;
        $controlDigit = $control < 2 ? $control : 11 - $control;

        return (int) $value[9] === $controlDigit;
    }

    /**
     * Validate Url
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateCheckUrl($attribute, $value, $parameters)
    {
        return preg_match("/^(HTTP|http(s)?:\/\/(www\.)?[A-Za-z0-9]+([\-\.]{1,2}[A-Za-z0-9]+)*\.[A-Za-z]{2,40}(:[0-9]{1,40})?(\/.*)?)$/", $value);
    }

    /**
     * Validate Domain
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateCheckDomain($attribute, $value, $parameters)
    {
        return preg_match("/^((www\.)?(\*\.)?[A-Za-z0-9]+([\-\.]{1,2}[A-Za-z0-9]+)*\.[A-Za-z]{2,40}(:[0-9]{1,40})?(\/.*)?)$/", $value);
    }

    /**
     * Validate iranian company id number (Shenase Melli Ashkhas Hoghoghi)
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateIranianCompanyId($attribute, $value, $parameters)
    {
        if (! preg_match('/^\d{11}$/', $value) || preg_match('/^(\d)\1{10}$/', $value)) {
            return false;
        }

        $multiplier = [29, 27, 23, 19, 17, 29, 27, 23, 19, 17];
        $checkNumber = substr($value, 10, 1);
        $decimalNumber = substr($value, 9, 1);
        $multiplication = $decimalNumber + 2;
        $sum = 0;

        for ($i = 0; $i < 10; $i++) {
            $sum += (substr($value, $i, 1) + $multiplication) * $multiplier[$i];
        }

        $remain = $sum % 11;
        if ($remain == 10) {
            $remain = 0;
        }

        return $remain == $checkNumber;
    }
}
