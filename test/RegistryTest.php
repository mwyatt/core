<?php

namespace Mwyatt\Core;

class RegistryTest extends \PHPUnit_Framework_TestCase
{


    public function testGetSet()
    {
        $registry = \Mwyatt\Core\Registry::getInstance();
        $registry->set('foo', 'bar');
        $this->assertEquals('bar', $registry->get('foo'));
    }
}
