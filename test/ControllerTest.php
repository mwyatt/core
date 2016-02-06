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
        $container = new \Pimple\Container;
        $container['thing'] = 'ok';
        echo '<pre>';
        print_r($container);
        echo '</pre>';
        exit;
        
    }


    public function testRedirectFail()
    {
        // how?
    }
}
