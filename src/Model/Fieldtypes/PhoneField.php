<?php

namespace Sunnysideup\PhoneField\Model\Fieldtypes;

use SilverStripe\Dev\Deprecation;
use SilverStripe\ORM\FieldType\DBVarchar;

/**
 * you can now use the following in your silverstripe templates
 * $MyPhoneField.TelLink
 * which then removes the first 0
 * adds country code at the end
 * and adds + and country code.
 *
 * e.g
 * 09 5556789
 * becomes
 * tel:+649555789
 *
 * if you would like a different country code then use:
 * $MyPhoneField.TelLink(55)
 */
class PhoneField extends DBVarchar
{
    private static $default_country_code = '64';

    private static $casting = [
        'Link' => 'Varchar',
        'Nice' => 'Varchar',
        'IntlFormat' => 'Varchar',
        'CallToLink' => 'Varchar',
        'TelLink' => 'Varchar',
        'TelLinkWithZero' => 'Varchar',
        'TellLink' => 'Varchar',
    ];

    /**
     * how the user enters it is how they want it!
     *
     * @return string
     */
    public function Link(?int $countryCode = null): DBVarchar
    {
        return $this->TellLink($countryCode);
    }

    public function Nice()
    {
        return self::create_field('Varchar', $this->value);
    }

    /**
     * @param int $countryCode (e.g. 64) - leave blank to use default, or set a different country code,
     *                         set to zero to have no country code.
     */
    public function IntlFormat(?int $countryCode = null): DBVarchar
    {
        $phoneNumber = $this->getProperPhoneNumber($countryCode);

        return self::create_field('Varchar', $phoneNumber);
    }

    /**
     * @param int $countryCode (e.g. 64) - leave blank to use default, or set a different country code,
     *                         set to zero to have no country code.
     */
    public function TelLinkWithZero(?int $countryCode = null): DBVarchar
    {
        $phoneNumber = 'tel:' . $this->getProperPhoneNumber($countryCode, true);

        /** @var DBVarchar $var */
        $var = self::create_field('Varchar', $phoneNumber);

        return $var;
    }

    /**
     * https://stackoverflow.com/questions/1164004/how-to-mark-up-phone-numbers
     * this is the better of the two.
     *
     * @param int $countryCode (e.g. 64) - leave blank to use default, or set a different country code,
     *                         set to zero to have no country code.
     */
    public function TelLink(?int $countryCode = null): DBVarchar
    {
        $phoneNumber = 'tel:' . $this->getProperPhoneNumber($countryCode);

        /** @var DBVarchar $var */
        $var = self::create_field('Varchar', $phoneNumber);

        return $var;
    }

    /**
     * https://stackoverflow.com/questions/1164004/how-to-mark-up-phone-numbers
     * this is the better of the two.
     *
     * @param int $countryCode (e.g. 64) - leave blank to use default, or set a different country code,
     *                         set to zero to have no country code.
     */
    public function TellLink(?int $countryCode = null): DBVarchar
    {
        Deprecation::notice('4.0', 'Use PhoneField::TelLink instead of PhoneField::TellLink - i.e. replace double "L" with a single "L" ... ');

        return $this->TelLink($countryCode);
    }

    /**
     * @param int $countryCode (e.g. 64) - leave blank to use default, or set a different country code,
     *                         set to zero to have no country code.
     */
    public function CallToLink(?int $countryCode = null): DBVarchar
    {
        $phoneNumber = 'callto:' . $this->getProperPhoneNumber($countryCode);

        /** @var DBVarchar $var */
        $var = self::create_field('Varchar', $phoneNumber);

        return $var;
    }

    /**
     * removes a string at the start of a string, if present...
     *
     * @param string $str    - the haystack
     * @param string $prefix - the needle
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
     *                         set to zero to have no country code.
     */
    protected function getProperPhoneNumber(?int $countryCode = null, ?bool $keepFirstZero = false): string
    {
        $v = $this->addCountryCode($countryCode, $keepFirstZero);
        $v = preg_replace('#\D#', '', (string) $v);
        return '+' . $v;
    }

    protected function addCountryCode(?int $countryCode = null, ?bool $keepFirstZero = false)
    {
        $v = $this->value;
        if ($v) {
            if (0 === strpos((string) $v, '+')) {
                return $v;
            }
            if (null === $countryCode) {
                $countryCode = $this->Config()->default_country_code;
            }
            if (0 === strpos((string) $v, '0')) {
                $v = $this->literalLeftTrim($v, '0');
            }
            if ($countryCode) {
                $v = $this->literalLeftTrim($v, $countryCode);
            }
            return '+' . $countryCode . $v;
        }
        return $v;
    }

    protected function removeCountryCode(?int $countryCode = null,)
    {
        $v = $this->value;
        if (null === $countryCode) {
            $countryCode = $this->Config()->default_country_code;
        }
        $v = $this->literalLeftTrim($v, '+');
        $v = $this->literalLeftTrim($v, $countryCode);
        return $v;
    }
}
