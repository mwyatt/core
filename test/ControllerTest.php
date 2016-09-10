<?php

namespace Mwyatt\Core;

class ControllerTest extends \PHPUnit_Framework_TestCase
{


    public $controller;


    public function __construct()
    {
        $this->controller = new \Mwyatt\Core\Controller\Test(new \Pimple\Container, new \Mwyatt\Core\View);
    }


    public function testResponse()
    {
        $this->assertInstanceOf('\Mwyatt\Core\ResponseInterface', $this->controller->response());
    }


    public function testGetService()
    {
    }


    public function testGetRepository()
    {
    }


    /**
     * @expectedException \Exception
     */
    public function testRedirectFail()
    {
        $this->controller->redirect('does-not-exist');
    }
}
