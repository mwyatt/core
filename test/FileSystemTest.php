<?php

namespace Mwyatt\Core;

class FileSystemTest extends \PHPUnit_Framework_TestCase
{
    private $fileSystem;


    public function setUp()
    {
        $this->fileSystem = new \Mwyatt\Core\FileSystem((string) (__DIR__ . '/') . '../template/');
    }


    public function testSetUp()
    {
        $this->assertTrue(is_object($this->fileSystem));
    }


    public function testGetDirectory()
    {
        $files = $this->fileSystem->getDirectory();
        $this->assertTrue(count($files) > 0);
    }


    public function testTidyFilesGlobal()
    {
        $filesGlobal = [
            'name' => ['example.jpg', 'example.jpg', 'example.jpg'],
            'tmp_name' => ['weh1231hui.jpg', 'weh1231hui.jpg', 'weh1231hui.jpg'],
            'size' => [1, 1, 1],
            'error' => [0, 0, 0],
        ];
        $files = $this->fileSystem->tidyFilesGlobal($filesGlobal);
        $this->assertTrue(count($files) === 3);
    }


    /**
     * @expectedException \Exception
     */
    public function testTidyFilesGlobalException()
    {
        $filesGlobal = [
            'name' => ['example.jpg', 'example.jpg', 'example.jpg'],
            'tmp_name' => ['weh1231hui.jpg', 'weh1231hui.jpg', 'weh1231hui.jpg'],
            'size' => [1, 1, 1],
        ];
        $files = $this->fileSystem->tidyFilesGlobal($filesGlobal);
    }
}
