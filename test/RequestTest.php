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
        $this->request = new \Mwyatt\Core\Request;
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
