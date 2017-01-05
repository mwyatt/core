<?php

namespace Mwyatt\Core;

class MonologTest extends \PHPUnit_Framework_TestCase
{


    public function testTrigger()
    {
        $basePath = (string) __DIR__ . '/../';
        $log = new \Monolog\Logger('core');
        $handler = new \Monolog\ErrorHandler($log);
        $log->pushHandler(new \Monolog\Handler\StreamHandler((string) (__DIR__ . '/../') . 'error.txt', \Monolog\Logger::WARNING));
        // \Monolog\ErrorHandler::register($log);
        $handler->registerErrorHandler([], false);
        $handler->registerExceptionHandler();
        $handler->registerFatalHandler();
        $log->warning('Foo');
        $log->error('Bar');

        // if (!file_exists($basePath . 'log/')) {
        //     mkdir($basePath . 'log/');
        // }
        // if (!file_exists($)) {
        //     # code...
        // }
    }
}
