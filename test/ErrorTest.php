<?php

namespace Mwyatt\Core;

class ErrorTest extends \PHPUnit_Framework_TestCase
{


    public function testHandle()
    {
        $error = new \Mwyatt\Core\Error;
        $error->handle($errorType, $errorString, $errorFile, $errorLine);
        
    }
}
