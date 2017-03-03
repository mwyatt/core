<?php

namespace Mwyatt\Core;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    private $routes = [
        [
            'any', '/',
            '\\Mwyatt\\Core\\Controller\\Test', 'testSimple',
            ['id' => 'test.simple']
        ],
        [
            'any', '/foo/:name/:id/',
            '\\Mwyatt\\Core\\Controller\\Test', 'testParams',
            ['id' => 'test.params']
        ],
        [
            'any', '/foo/bar/',
            '\\Mwyatt\\Core\\Controller\\Test', 'testSimple',
            ['id' => 'test.simple.submit']
        ]
    ];
    private $router;
    private $request;


    public function setUp()
    {
        $this->router = new \Mwyatt\Core\Router(
            new \Pux\Mux,
            $this->routes
        );
        $cookie = new \Mwyatt\Core\Cookie;
        $session = new \Mwyatt\Core\Session;
        $this->request = new \Mwyatt\Core\Request($session, $cookie);
    }


    public function testGetMatch()
    {
        $route = $this->router->getMatch('/flob/');
        $this->assertTrue(is_null($route));
        $route = $this->router->getMatch('/');
        $this->assertTrue(is_object($route));
    }


    public function testGetRoutes()
    {
        $routes = $this->router->getRoutes();
        $this->assertTrue(is_object($routes));
    }


    public function testGetDetails()
    {
        $route = $this->router->getMatch('/');
        $this->assertTrue($route->controller === '\Mwyatt\Core\Controller\Test');
        $this->assertTrue($route->method === 'testSimple');
        $this->assertTrue($route->getOption('id') === 'test.simple');
    }


    public function testUrlVars()
    {
        $route = $this->router->getMatch('/foo/david/123/');
        $this->request->setMuxUrlVars($route);
        $this->assertTrue($this->request->getUrlVar('name') === 'david');
        $this->assertTrue($this->request->getUrlVar('id') === '123');
    }
}
