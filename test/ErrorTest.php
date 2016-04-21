<?php

namespace Mwyatt\Core;

class ErrorTest extends \PHPUnit_Framework_TestCase
{


    public function testHandle()
    {
        $error = new \Mwyatt\Core\Error;
        $this->assertTrue($error->handle('type', 'string', 'file', 'line') > 0);
    }
}
