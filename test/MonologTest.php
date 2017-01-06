<?php

namespace Mwyatt\Core;

class MonologTest extends \PHPUnit_Framework_TestCase
{
    private $basePath;


    public function setUp()
    {
        $this->basePath = (string) __DIR__ . '/../';
        $paths = glob($this->basePath . 'cache/log/*');
        foreach ($paths as $path) {
            unlink($path);
        }
    }


    public function testTrigger()
    {
        $log = new \Monolog\Logger('error');
        $files = new \Monolog\Handler\RotatingFileHandler(
            $this->basePath . 'cache/log/error.log',
            30
        );
        $lineFormatter = new \Monolog\Formatter\LineFormatter;
        $lineFormatter->includeStacktraces();
        $files->setFormatter($lineFormatter);
        $log->pushHandler($files);
        $log->warning('Foo');
        $log->error('Bar');
        $paths = glob($this->basePath . 'cache/log/*');
        $this->assertTrue(count($paths) === 1);
    }
}
