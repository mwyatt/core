<?php

namespace Mwyatt\Core;

class FileSystemTest extends \PHPUnit_Framework_TestCase
{


    public function testSetPathBase()
    {
        $fileSystem = new \Mwyatt\Core\FileSystem('./template/');
        $this->assertTrue(is_object($fileSystem));
    }


    public function testGetFile()
    {
        $fileSystem = new \Mwyatt\Core\FileSystem('./template/');
        // $file = $fileSystem->getFile('footer/');
        $file = $fileSystem->getDirectory();
        // $this->assertTrue();
        echo '<pre>';
        print_r($file);
        echo '</pre>';
        exit;

    }
}
