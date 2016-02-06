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
    {
        $key = 'thing';
        $value = 'ok';
        $container = new \Pimple\Container;
        $container[$key] = $value;
        $controller = new \Mwyatt\Core\Controller($container, new \Mwyatt\Core\View);
        $this->assertEquals($value, $controller->get($key));
    }


    public function testRedirectFail()
    {
        // how?
    }
}
