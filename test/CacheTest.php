<?php

namespace Mwyatt\Core;

class CacheTest extends \PHPUnit_Framework_TestCase
{
    public $fileName = 'bar.foo';


    public function testCreate()
    {
        $cache = new \Mwyatt\Core\Cache;
        $data = 'foo-bar';
        $this->assertGreaterThan(0, $cache->create($this->fileName, $data));
    }


    public function testRead()
    {
        $cache = new \Mwyatt\Core\Cache;
        $this->assertEquals('foo-bar', $cache->read($this->fileName));
    }


    public function testDelete()
    {
        $cache = new \Mwyatt\Core\Cache;
        $this->assertTrue($cache->delete($this->fileName));
    }
}
