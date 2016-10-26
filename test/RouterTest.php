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
            'post', '/foo/bar/',
            '\\Mwyatt\\Core\\Controller\\Test', 'testSimple'
        ]
    ];
    private $router;
    private $request;


    public function setUp()
    {
        $container = new \Pimple\Container;
        $container['PuxMux'] = function ($container) {
            return new \Pux\Mux;
        };
        $container['Router'] = function ($container) {
            return new \Mwyatt\Core\Router($container['PuxMux']);
        };
        $container['Request'] = function ($container) {
            $cookie = new \Mwyatt\Core\Cookie;
            $session = new \Mwyatt\Core\Session;
            return new \Mwyatt\Core\Request($session, $cookie);
        };
        $this->request = $container['Request'];
        $this->router = $container['Router'];
        $this->router->appendRoutes($this->routes);
    }


    public function testGetMatch()
    {
        $route = $this->router->getMatch('/flob/');
        $this->assertTrue(is_null($route));
        $route = $this->router->getMatch('/');
        $this->assertTrue(is_array($route));
    }


    public function testGetDetails()
    {
        $route = $this->router->getMatch('/');
        $this->assertTrue($this->router->getRouteControllerName($route) === '\Mwyatt\Core\Controller\Test');
        $this->assertTrue($this->router->getRouteControllerMethod($route) === 'testSimple');
    }


    public function testUrlVars()
    {
        $route = $this->router->getMatch('/foo/david/123/');
        $this->request->setMuxUrlVars($route);
        $this->assertTrue($this->request->getUrlVar('name') === 'david');
        $this->assertTrue($this->request->getUrlVar('id') === '123');
    }
}
