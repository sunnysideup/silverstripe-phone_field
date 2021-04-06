<?php

use SilverStripe\Dev\SapphireTest;

/**
 * @internal
 * @coversNothing
 */
class PhoneFieldTest extends SapphireTest
{
    protected $usesDatabase = false;

    protected $required_extensions = [];

    public function TestDevBuild()
    {
        $exitStatus = shell_exec('php framework/cli-script.php dev/build flush=all  > dev/null; echo $?');
        $exitStatus = (int) trim($exitStatus);
        $this->assertSame(0, $exitStatus);
    }
}
