<?php

namespace Mwyatt\Core;

class RequestTest extends \PHPUnit_Framework_TestCase
{


	public function testGet()
	{
		$_GET['foo'] = 'bar';
		$request = new \Mwyatt\Core\Request;
		$this->assertEquals('foo', $request->get('foo'));
		
		unset($_GET['foo']);
		$this->assertEquals('', $request->get('foo'));
	}
}
