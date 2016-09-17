<?php

namespace Mwyatt\Core;

class IteratorTest extends \PHPUnit_Framework_TestCase
{
    public $iterator;


    public function setUp()
    {
        $this->iterator = new \Mwyatt\Core\Iterator;
    }


    public function testOffsetAppend()
    {
        $filePath = 'foo/bar.bundle.css';
        $filePathAlt = 'foo/bar/so.bundle.css';
        $this->iterator->offsetSet('css', [$filePath]);
        $this->iterator->offsetAppend('css', $filePathAlt);
        $offset = $this->iterator->offsetGet('css');
        $this->assertTrue(is_array($offset));
        $this->assertTrue(count($offset) === 2);
        $this->assertTrue($offset[0] === $filePath);
        $this->assertTrue($offset[1] === $filePathAlt);
    }
}
