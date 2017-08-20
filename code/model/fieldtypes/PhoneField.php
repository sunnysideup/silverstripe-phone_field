<?php
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

class PhoneField extends Varchar
{
    private static $default_country_code = "64";

    private static $casting = array(
        "TellLink" => "Varchar",
        "CallToLink" => "Varchar"
    );

    /**
     * This method is accessed by other pages!
     *
     * @param int $countryCode (e.g. 64)
     *
     * @return string
     */
    public function TellLink($countryCode = "")
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
        //combine
        $phoneNumber = 'tel:+'.$countryCode.$phoneNumber;
        
        return $phoneNumber;
    }



    /**
     *
     * @param countryCode $countryCode (e.g. 64)
     *
     * @return string
     */
    public function CallToLink($countryCode = "")
    {
        return str_replace('tel:', 'callto:', $this->TellLink($countryCode));
    }
    
    /**
     * @see DBField::scaffoldFormField()
     *
     * @param string $title (optional)
     * @param array $params (optional)
     *
     * @return PhoneNumberField | NullableField
     */
    public function scaffoldFormField($title = null, $params = null)
    {
        if (!$this->nullifyEmpty) {
            return NullableField::create(PhoneNumberField::create($this->name, $title));
        } else {
            return PhoneNumberField::create($this->name, $title);
        }
    }
    
    /**
     * removes a string at the start of a string, if present...
     * @param string $str - the haystack
     * @param string $prefix - the needle
     *
     * @return string
     */
    protected function literalLeftTrim($str, $prefix)
    {
        if (substr($str, 0, strlen($prefix)) == $prefix) {
            $str = substr($str, strlen($prefix));
        }     
        return $str;
    }
    
}
