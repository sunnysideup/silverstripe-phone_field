# Phone Field

Provides a database field for Silverstripe. This turns a local phone number
e.g. `(09) 7894 4332` into a phone number that can be used by the device to call
e.g. `+31978944332`.  You can set a default country code or specify a custom one.

There is also a callto link which is the preferred method for skype.

# Usage

```php
<?php
    class MyClass extends DataObject
    {
        private static $db = [
            'MyPhoneNumber' => 'PhoneField'
        ];

    }
```

in your `app/_config/config.yml` you can add:

```yml
Sunnysideup\PhoneField\Model\Fieldtypes\PhoneField:
  default_country_code: 31
```

in your template files you can now write:

```html
call us: <a href="$MyPhoneNumber.TelLink">$MyPhoneNumber.IntlFormat</a>
<br />

call us on skype: <a href="$MyPhoneNumber.CallToLink">$MyPhoneNumber</a>
<br />

calls us in Germany: <a href="$MyPhoneNumber.TelLink(49)">$MyPhoneNumber</a>
<br />

calls us only from within your country: <a href="$MyPhoneNumber.TelLink(0)">$MyPhoneNumber</a>
<br />
```

Instead of `TelLink` you can also use `CallToLink`.  However, the `TelLink` is recommended.

see: https://stackoverflow.com/questions/1164004/how-to-mark-up-phone-numbers

Also available
