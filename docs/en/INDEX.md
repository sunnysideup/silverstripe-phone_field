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
        private static $db = array("MyPhoneNumber" => "PhoneField");

    }
```

in your `mysite/_config/config.yml` you can add:

```yml
PhoneField:
  default_country_code: 31
```

in your template files you can now write:

```html
call us: <a href="$MyPhoneNumber.TellLink">$MyPhoneNumber</a><br />

call us on skype: <a href="$MyPhoneNumber.CallLink">$MyPhoneNumber</a><br />

calls us in Germany: <a href="$MyPhoneNumber.TellLink(49)">$MyPhoneNumber</a><br />
```
