<?php
namespace Mwyatt\Core;

class RouterTest extends \PHPUnit_Framework_TestCase
{


    public function test200()
    {
        $expectedCode = 200;

        // build routes
        $route = new \Mwyatt\Core\Entity\Route;
        $route->type = 'any';
        $route->key = 'home';
        $route->path = '/';
        $route->controller = 'Mwyatt\\Core\\Controller';
        $route->method = 'home';

        // get response
        $router = new \Mwyatt\Core\Router;
        $router->appendRoutes([$route]);
        $response = $router->getResponse('/');

        // test
        $this->assertEquals($expectedCode, $response->getContent());
        $this->assertEquals($expectedCode, $response->getStatusCode());
    }


    public function test404()
    {
        $expectedCode = 404;

        // get response
        $router = new \Mwyatt\Core\Router;
        $response = $router->getResponse('/');

        // test
        $this->assertEquals($expectedCode, $response->getContent());
        $this->assertEquals($expectedCode, $response->getStatusCode());
    }


    public function test500()
    {
        // $expectedCode = 500;

        // // get response
        // $router = new \Mwyatt\Core\Router;
        // $response = $router->getResponse('/');

        // // test
        // $this->assertEquals($expectedCode, $response->getContent());
        // $this->assertEquals($expectedCode, $response->getStatusCode());
    }
}
