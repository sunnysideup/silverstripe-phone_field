<?php

namespace Sunnysideup\PhoneField\Model\Fieldtypes;

use SilverStripe\ORM\FieldType\DBVarchar;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\NullableField;

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
    private static $default_country_code = "64";

    private static $casting = [
        "TellLink" => "Varchar",
        "CallToLink" => "Varchar",
    ];


    /**
     * This method is accessed by other pages!
     *
     * @param string|int|null $countryCode (e.g. 64)
     *
     * @return DBVarchar
     */
    public function IntlFormat($countryCode = "") : string
    {
        $phoneNumber = $this->getProperPhoneNumber($countryCode);
        
        return self::create_field('Varchar', $phoneNumber);
    }

    /**
     * This method is accessed by other pages!
     *
     * @param string|int|null $countryCode (e.g. 64)
     *
     * @return DBVarchar
     */
    public function TellLink($countryCode = "") : string
    {
        $phoneNumber = 'tel:'.$this->getProperPhoneNumber($countryCode);
        
        return self::create_field('Varchar', $phoneNumber);        
    }



    /**
     *
     * @param int|string|null $countryCode (e.g. 64)
     *
     * @return string
     */
    public function CallToLink($countryCode = "") : string
    {
        $phoneNumber = 'callto:'.$this->getProperPhoneNumber($countryCode);
        
        return self::create_field('Varchar', $phoneNumber);             
    }

    /**
     * removes a string at the start of a string, if present...
     * @param string $str - the haystack
     * @param string $prefix - the needle
     *
     * @return string
     */
    protected function literalLeftTrim(string $str, string $prefix) : string
    {
        if (substr($str, 0, strlen($prefix)) === $prefix) {
            $str = substr($str, strlen($prefix));
        }
        return $str;
    }

    /**
     *
     * @param int|string|null $countryCode (e.g. 64)
     *
     * @return string
     */    
    protected function  getProperPhoneNumber($countryCode = "") : string
    {
        //remove non digits
        if (!$countryCode) {
            $countryCode = $this->Config()->default_country_code;
        }
        //remove non-digits
        $phoneNumber = preg_replace('/\D/', '', $this->value);
        //remove country code with plus - NOT NECESSARY
        //$phoneNumber = $this->literalLeftTrim($phoneNumber, '+'.$countryCode);
        //remove country code
        $phoneNumber = $this->literalLeftTrim($phoneNumber, $countryCode);
        //remove leading zero
        $phoneNumber = $this->literalLeftTrim($phoneNumber, '0');

        return '+'.$countryCode.$phoneNumber;        
    }
}
