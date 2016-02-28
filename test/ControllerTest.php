<?php

namespace Mwyatt\Core;

class ControllerTest extends \PHPUnit_Framework_TestCase
{


    public $controller;


    public function __construct()
    {
        $this->controller = new \Mwyatt\Core\Controller(
            new \Pimple\Container,
            new \Mwyatt\Core\View
        );
    }


    public function testResponse()
    {
        $this->assertInstanceOf('\Mwyatt\Core\ResponseInterface', $this->controller->response());
    }


    public function testService()
    {}


    /**
     * @expectedException \Exception
     */
    public function testRedirectFail()
    {
        $this->controller->redirect('does-not-exist');
    }


    // public function testRedirectSuccess()
    // {
    //     $redirect = $this->controller->redirect();
    //     $this->assertTrue($redirect);
    // }
}
