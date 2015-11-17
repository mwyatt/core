<?php

namespace Mwyatt\Core;

class CacheTest extends \PHPUnit_Framework_TestCase
{


    public function testSetGetKey()
    {
        $cache = new \Mwyatt\Core\Cache('unique-name-cache');
        $this->assertEquals('unique-name-cache', $cache->getKey());
    }


    public function testCreate()
    {
        $cache = new \Mwyatt\Core\Cache('unique-name-cache');
        $data = 'foo-bar';
        $this->assertTrue($cache->create($data));
    }


    public function testRead()
    {
        $cache = new \Mwyatt\Core\Cache('unique-name-cache');
        $cache->read();
        $this->assertEquals('foo-bar', $cache->getData());
    }


    public function testDelete()
    {
        $cache = new \Mwyatt\Core\Cache('unique-name-cache');
        $this->assertTrue($cache->delete());
    }


    public function testFlush()
    {
        // $cache = new \Mwyatt\Core\Cache;
        // $cache->flush();
    }
}
