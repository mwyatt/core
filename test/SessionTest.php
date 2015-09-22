<?php

namespace Mwyatt\Core;

class SessionTest extends \PHPUnit_Framework_TestCase
{


    public function testConstruct()
    {
        $GLOBALS['_SESSION'] = [];
        $sessionFooBar = new \Mwyatt\Core\Session('foo/bar');
        $this->assertArrayHasKey('foo/bar', $_SESSION);
    }


    public function testSetGetData()
    {
        $GLOBALS['_SESSION'] = [];
        $sessionFooBar = new \Mwyatt\Core\Session('foo/bar');
        $sessionFooBar->setData('foo');
        $this->assertEquals('foo', $sessionFooBar->getData());
    }


    public function testSetGetDataKey()
    {
        $GLOBALS['_SESSION'] = [];
        $sessionFooBar = new \Mwyatt\Core\Session('foo/bar');
        $sessionFooBar->setDataKey('foo', 'bar');
        $this->assertEquals('bar', $sessionFooBar->getDataKey('foo'));
    }


    public function testPullData()
    {
        $GLOBALS['_SESSION'] = [];
        $sessionFooBar = new \Mwyatt\Core\Session('foo/bar');
        $sessionFooBar->setData('foo');
        $this->assertEquals('foo', $sessionFooBar->pullData());
        $this->assertEquals('', $sessionFooBar->pullData());
    }


    public function testPullDataKey()
    {
        $GLOBALS['_SESSION'] = [];
        $sessionFooBar = new \Mwyatt\Core\Session('foo/bar');
        $sessionFooBar->setDataKey('foo', 'bar');
        $this->assertEquals('bar', $sessionFooBar->pullDataKey('foo'));
        $this->assertEquals('', $sessionFooBar->pullDataKey('foo'));
    }
}
