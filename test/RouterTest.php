<?php

namespace Mwyatt\Core;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    public $routes = [
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
    public $controller;


    public function setUp()
    {
        $container = new \Pimple\Container;
        $container['PuxMux'] = function ($container) {
            return new \Pux\Mux;
        };
        $container['Router'] = function ($container) {
            return new \Mwyatt\Core\Router($container['PuxMux']);
        };
        $this->router = $container['Router'];
    }


    public function testGetSimple()
    {
        $view = new \Mwyatt\Core\View;
        $router = new \Mwyatt\Core\Router(new \Pux\Mux);
        $request = new \Mwyatt\Core\Request();

        $routes = array_merge($this->routes);
        $router->appendMuxRoutes($routes);


        /**
         * simple
         */
        $url = new \Mwyatt\Core\Url('192.168.1.24/', '/core/', 'core/');
        $route = $router->getMuxRouteCurrent($url->getPath());
        $request->setMuxUrlVars($route);
        
        $controllerNs = $router->getMuxRouteCurrentController();
        $controllerMethod = $router->getMuxRouteCurrentControllerMethod();

        $this->assertEquals('\Mwyatt\Core\Controller\Test', $controllerNs);
        $this->assertEquals('testSimple', $controllerMethod);

        $controller = new $controllerNs(new \Pimple\Container, $view);
        $response = $controller->$controllerMethod($request);

        $this->assertInstanceOf('\Mwyatt\Core\ResponseInterface', $response);
        $this->assertEquals('testSimpleContent', $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());


        /**
         * params
         */
        $url = new \Mwyatt\Core\Url('192.168.1.24/', '/core/foo/bar-boo/197/', 'core/');
        $route = $router->getMuxRouteCurrent($url->getPath());
        $request->setMuxUrlVars($route);
        
        $controllerNs = $router->getMuxRouteCurrentController();
        $controllerMethod = $router->getMuxRouteCurrentControllerMethod();

        $this->assertEquals('\Mwyatt\Core\Controller\Test', $controllerNs);
        $this->assertEquals('testParams', $controllerMethod);

        $response = $controller->$controllerMethod($request);

        $this->assertInstanceOf('\Mwyatt\Core\ResponseInterface', $response);
        $this->assertEquals('testParamsContent, bar-boo, 197', $response->getContent());
        $this->assertEquals(500, $response->getStatusCode());
    }
}
