<?php

namespace Mwyatt\Core;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    public $request;


    public function __construct()
    {
        $_GET['foo'] = 'bar';
        $_POST['foo'] = 'bar';
        $_SESSION['foo'] = 'bar';
        $container = new \Pimple\Container;
        $container['Request'] = function ($container) {
            $cookie = new \Mwyatt\Core\Cookie;
            $session = new \Mwyatt\Core\Session;
            return new \Mwyatt\Core\Request($session, $cookie);
        };
        $this->request = $container['Request'];
    }


    public function testGetQuery()
    {
        $this->assertEquals('bar', $this->request->getQuery('foo'));
        $this->assertEquals('bar', $this->request->getPost('foo'));
    }


    public function testSession()
    {
        $this->assertEquals('bar', $this->request->getSession('foo'));

        $this->request->setSession('doo', 'daah');
        $this->assertEquals('daah', $this->request->getSession('doo'));

        $this->assertEquals('daah', $this->request->pullSession('doo'));
        $this->assertEquals(null, $this->request->pullSession('doo'));
    }
}
