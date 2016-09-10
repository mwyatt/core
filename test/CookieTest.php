<?php

namespace Mwyatt\Core;

class CookieTest extends \PHPUnit_Framework_TestCase
{


    public function setUp()
    {
        $GLOBALS['_COOKIE'] = [];
    }


    public function testSetGet()
    {
        $cookie = new \Mwyatt\Core\Cookie;
        $cookie->set('foo', 'bar', time() + 999);
        $this->assertTrue($cookie->get('foo') === 'bar');
    }
}
