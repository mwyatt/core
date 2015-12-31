<?php

namespace Mwyatt\Core;

class ControllerTest extends \PHPUnit_Framework_TestCase
{


	public $controller;


    public function __construct()
    {
    	$this->controller = new \Mwyatt\Core\Controller(
    		new \Mwyatt\Core\ServiceFactory,
    		new \Mwyatt\Core\View
		);
    }


    public function testResponse()
    {
    	$this->assertInstanceOf('\Mwyatt\Core\ResponseInterface', $this->controller->response());
    }


    public function testService()
    {
    	// not yet
    }


    public function testRedirect()
    {
    	// how?
    }
}
