<?php


class PhoneField extends Varchar {


    private static $default_country_code = "64";

    private static $casting = array(
        "TellLink" => "Varchar",
        "CallToLink" => "Varchar"
    );

    /**
     * This method is accessed by other pages!
     *
     * @param countryCode $countryCode (e.g. 64)
     *
     * @return string
     */
    public function TellLink($countryCode = "")
    {
        //remove non digits
        if(!$countryCode) {
            $countryCode = $this->Config()->default_country_code;
        }
        $phoneNumber = preg_replace('/\D/', '', $this->value);

        //hack the 1300 scenario
        //if (substr($phoneNumber, 0, 4) == '1300') {
        //    $phoneNumber = preg_replace('/^1300/', '+611300', $phoneNumber);
        //} else {
            //remove leading zero
            $phoneNumber = preg_replace('/^0/', '+'.$countryCode, $phoneNumber);
        //}
        //remove double-ups
        $phoneNumber = str_replace(
            '+'.$countryCode.''.$countryCode,
            '+'.$countryCode,
            $phoneNumber
        );
        $phoneNumber = "tel:+".$phoneNumber;
        return $phoneNumber;
    }



    /**
     * This method is accessed by other pages!
     *
     * @param countryCode $countryCode (e.g. 64)
     *
     * @return string
     */
    public function CallToLink($countryCode = "")
    {
        return str_replace('tel:', 'callto:', $this->TellLink());
    }




}
