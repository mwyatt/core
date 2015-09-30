<?php

namespace Mwyatt\Core;

class UrlTest extends \PHPUnit_Framework_TestCase
{


    public function testConstruct()
    {
        $urlBase = '192.168.1.24/core/';
        $_SERVER['HTTP_HOST'] = '192.168.1.24';
        $_SERVER['REQUEST_URI'] = '/core/foo/bar/';
        $url = new \Mwyatt\Core\Url($urlBase);
        $this->assertEquals($urlBase, $url->getBase());
    }


    public function testGetPath()
    {
        $urlBase = '192.168.1.24/core/';
        $_SERVER['HTTP_HOST'] = '192.168.1.24';

        // filled
        $_SERVER['REQUEST_URI'] = '/core/foo/bar/';
        $url = new \Mwyatt\Core\Url($urlBase);
        $this->assertEquals('foo/bar/', $url->getPath());
        
        // empty
        $_SERVER['REQUEST_URI'] = '/core/';
        $url = new \Mwyatt\Core\Url($urlBase);
        $this->assertEquals('', $url->getPath());
    }


    public function testGenerate()
    {

        // url
        $urlBase = '192.168.1.24/core/';
        $_SERVER['HTTP_HOST'] = '192.168.1.24';
        $_SERVER['REQUEST_URI'] = '/core/';
        $url = new \Mwyatt\Core\Url($urlBase);

        // route
        $route = new \Mwyatt\Core\Entity\Route;
        $route->type = 'get';
        $route->key = 'foo/bar';
        $route->path = '/foo/:bar/';
        $route->controller = 'Mwyatt\\Core\\Controller\\Foo';
        $route->method = 'Bar';
        $url->setRoutes([$route]);

        // test
        $this->assertEquals('http://192.168.1.24/core/foo/1/', $url->generate('foo/bar', ['bar' => 1]));
    }


    public function testGenerateVersioned()
    {

        // url
        $urlBase = '192.168.1.24/core/';
        $_SERVER['HTTP_HOST'] = '192.168.1.24';
        $_SERVER['REQUEST_URI'] = '/core/foo/bar/';
        $url = new \Mwyatt\Core\Url($urlBase);

        // view
        $this->assertContains('asset/test.css', $url->generateVersioned('asset/test.css'));
    }
}
