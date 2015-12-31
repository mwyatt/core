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


	public function testGet()
	{
		$this->assertEquals('bar', $this->request->get('foo'));
		$this->assertEquals('bar', $this->request->getPost('foo'));
		$this->assertEquals('bar', $this->request->getSession('foo'));
	}
}
