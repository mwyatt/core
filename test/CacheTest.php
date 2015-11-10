<?php

namespace Mwyatt\Core;

class CacheTest extends \PHPUnit_Framework_TestCase
{


    public function testCreate()
    {
        $data = 'foo-bar';
        $cache = new \Mwyatt\Core\Cache;
        $cache->setKey('foo-bar');
        $this->assertTrue($cache->create($data));
    }


    public function testRead()
    {
        $cache = new \Mwyatt\Core\Cache;
        $this->assertEquals('foo-bar', $cache->read('foo/bar/foo-bar'));
    }
}
