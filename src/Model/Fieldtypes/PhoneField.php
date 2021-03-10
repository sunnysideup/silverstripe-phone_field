<?php

namespace Sunnysideup\PhoneField\Model\Fieldtypes;

use SilverStripe\ORM\FieldType\DBVarchar;

/**
 * you can now use the following in your silverstripe templates
 * $MyPhoneField.TellLink
 * which then removes the first 0
 * adds country code at the end
 * and adds + and country code
 *
 * e.g
 * 09 5556789
 * becomes
 * tel:+649555789
 *
 * if you would like a different country code then use:
 * $MyPhoneField.TellLink(55)
 */

class PhoneField extends DBVarchar
{
    private static $default_country_code = '64';

    private static $casting = [
        'TellLink' => 'Varchar',
        'CallToLink' => 'Varchar',
    ];

    /**
     * This method is accessed by other pages!
     *
     * @param int $countryCode (e.g. 64) - leave blank to use default, or set a different country code,
     *                                     set to zero to have no country code.
     *
     * @return DBVarchar
     */
    public function IntlFormat(?int $countryCode = null): DBVarchar
    {
        $phoneNumber = $this->getProperPhoneNumber($countryCode);

        return self::create_field('Varchar', $phoneNumber);
    }

    /**
     * This method is accessed by other pages!
     *
     * @param int $countryCode (e.g. 64) - leave blank to use default, or set a different country code,
     *                                     set to zero to have no country code.
     *
     * @return DBVarchar
     */
    public function TellLink(?int $countryCode = null): DBVarchar
    {
        $phoneNumber = 'tel:' . $this->getProperPhoneNumber($countryCode);

        /** @var DBVarchar */
        $var = self::create_field('Varchar', $phoneNumber);
        $var->RAW();

        return $var;
    }

    /**
     * @param int $countryCode (e.g. 64) - leave blank to use default, or set a different country code,
     *                                     set to zero to have no country code.
     *
     * @return DBVarchar
     */
    public function CallToLink(?int $countryCode = null): DBVarchar
    {
        $phoneNumber = 'callto:' . $this->getProperPhoneNumber($countryCode);

        /** @var DBVarchar */
        $var = self::create_field('Varchar', $phoneNumber);
        $var->RAW();

        return $var;
    }

    /**
     * removes a string at the start of a string, if present...
     * @param string $str - the haystack
     * @param string $prefix - the needle
     *
     * @return string
     */
    protected function literalLeftTrim(string $str, string $prefix): string
    {
        if (substr($str, 0, strlen($prefix)) === $prefix) {
            $str = substr($str, strlen($prefix));
        }
        return $str;
    }

    /**
     * @param int $countryCode (e.g. 64) - leave blank to use default, or set a different country code,
     *                                     set to zero to have no country code.
     *
     * @return string
     */
    protected function getProperPhoneNumber(?int $countryCode = null): string
    {
        //remove non-digits
        $phoneNumber = preg_replace('/\D/', '', $this->value);

        $hasCountryCode = true;
        if ($countryCode === null) {
            $countryCode = $this->Config()->default_country_code;
        }
        if ($countryCode) {
            //remove country code with plus - NOT NECESSARY
            //$phoneNumber = $this->literalLeftTrim($phoneNumber, '+'.$countryCode);
            //remove country code
            $phoneNumber = $this->literalLeftTrim($phoneNumber, $countryCode);
            //remove leading zero
            $phoneNumber = $this->literalLeftTrim($phoneNumber, '0');
        } else {
            $hasCountryCode = false;
        }

        return ($hasCountryCode ? '+' . $countryCode : '') . $phoneNumber;
    }
}
