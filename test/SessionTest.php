<?php

namespace Mwyatt\Core;

class SessionTest extends \PHPUnit_Framework_TestCase
{


    public function setUp()
    {
        $GLOBALS['_SESSION'] = [];
    }


    public function testSetGet()
    {
        $session = new \Mwyatt\Core\Session;
        $session->set('foo', 'bar');
        $this->assertTrue($session->get('foo') === 'bar');
    }


    public function testPull()
    {
        $session = new \Mwyatt\Core\Session;
        $session->set('foo', 'bar');
        $this->assertTrue($session->pull('foo') === 'bar');
        $this->assertTrue(!$session->pull('foo'));
        $this->assertTrue(!$session->get('foo'));
    }
}
