<?php

namespace Mwyatt\Core;

class FileSystemTest extends \PHPUnit_Framework_TestCase
{


    public function testSetPathBase()
    {
        $fileSystem = new \Mwyatt\Core\FileSystem((string) (__DIR__ . '/') . '../template/');
        $this->assertTrue(is_object($fileSystem));
    }


    public function testGetDirectory()
    {
        $fileSystem = new \Mwyatt\Core\FileSystem((string) (__DIR__ . '/') . '../template/');
        $files = $fileSystem->getDirectory();
        $this->assertTrue(count($files) > 0);
    }
}
