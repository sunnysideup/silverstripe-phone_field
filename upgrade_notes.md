2019-06-18 11:05

# running php upgrade upgrade see: https://github.com/silverstripe/silverstripe-upgrader
cd /var/www/upgrades/upgradeto4
php /var/www/upgrader/vendor/silverstripe/upgrader/bin/upgrade-code upgrade /var/www/upgrades/upgradeto4/phone_field  --root-dir=/var/www/upgrades/upgradeto4 --write -vvv --prompt
Writing changes for 2 files
Running upgrades on "/var/www/upgrades/upgradeto4/phone_field"
[2019-06-18 11:05:07] Applying RenameClasses to PhoneFieldTest.php...
[2019-06-18 11:05:07] Applying ClassToTraitRule to PhoneFieldTest.php...
[2019-06-18 11:05:07] Applying RenameClasses to PhoneField.php...
[2019-06-18 11:05:07] Applying ClassToTraitRule to PhoneField.php...
[2019-06-18 11:05:07] Applying RenameClasses to _config.php...
[2019-06-18 11:05:07] Applying ClassToTraitRule to _config.php...
modified:	tests/PhoneFieldTest.php
@@ -1,4 +1,6 @@
 <?php
+
+use SilverStripe\Dev\SapphireTest;

 class PhoneFieldTest extends SapphireTest
 {

modified:	src/Model/Fieldtypes/PhoneField.php
@@ -3,8 +3,11 @@
 namespace Sunnysideup\PhoneField\Model\Fieldtypes;

 use Varchar;
-use NullableField;
-use TextField;
+
+
+use SilverStripe\Forms\TextField;
+use SilverStripe\Forms\NullableField;
+

 /**
  * you can now use the following in your silverstripe templates

Writing changes for 2 files
✔✔✔
# running php upgrade inspect see: https://github.com/silverstripe/silverstripe-upgrader
cd /var/www/upgrades/upgradeto4
php /var/www/upgrader/vendor/silverstripe/upgrader/bin/upgrade-code inspect /var/www/upgrades/upgradeto4/phone_field  --root-dir=/var/www/upgrades/upgradeto4 --write -vvv
Array
(
    [0] => Running post-upgrade on "/var/www/upgrades/upgradeto4/phone_field"
    [1] => [2019-06-18 11:05:20] Applying ApiChangeWarningsRule to PhoneFieldTest.php...
    [2] => PHP Fatal error:  Class 'Varchar' not found in /var/www/upgrades/upgradeto4/phone_field/src/Model/Fieldtypes/PhoneField.php on line 28
)
