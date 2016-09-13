<?php

namespace Mwyatt\Core;

class ErrorTest extends \PHPUnit_Framework_TestCase
{


    public function testHandle()
    {
        // $error = new \Mwyatt\Core\Error;
        // $this->assertTrue($error->handle('type', 'string', 'file', 'line') > 0);
    }


    public function testTrigger()
    {
        // $error = new \Mwyatt\Core\Error('error.txt');
        // $result = trigger_error('Example error message', E_USER_NOTICE);

        $log = new \Monolog\Logger('core');
        $log->pushHandler(new \Monolog\Handler\StreamHandler((string) (__DIR__ . '/../') . 'error.txt', \Monolog\Logger::WARNING));

        // \Monolog\ErrorHandler::register($log);

$handler = new \Monolog\ErrorHandler($log);
$handler->registerErrorHandler([], false);
$handler->registerExceptionHandler();
$handler->registerFatalHandler();

echo $hi;

        $log->warning('Foo');
        $log->error('Bar');
    }
}
