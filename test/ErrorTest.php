<?php

namespace Mwyatt\Core;

class ErrorTest extends \PHPUnit_Framework_TestCase
{


    public function testHandle()
    {
        $error = new \Mwyatt\Core\Error;
        $this->assertTrue($error->handle('type', 'string', 'file', 'line') > 0);
    }


    public function testTrigger()
    {
        $error = new \Mwyatt\Core\Error('error.txt');
        set_error_handler(array($error, 'handle'));
        $result = trigger_error('Example error message', E_USER_NOTICE);

        $this->assertTrue($result);
    }
}
