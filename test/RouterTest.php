<?php
namespace Mwyatt\Core;

class RouterTest extends \PHPUnit_Framework_TestCase
{


    public function test200()
    {
        $expectedCode = 200;

        // build routes
        $route = new \Mwyatt\Core\Entity\Route;
        $route->type = 'get';
        $route->key = 'foo/bar';
        $route->path = '/foo/:bar/';
        $route->controller = 'Mwyatt\\Core\\Controller\\Foo';
        $route->method = 'Bar';

        // get response
        $router = new \Mwyatt\Core\Router;
        $router->appendRoutes([$route]);
        $response = $router->getResponse('/foo/bar/');

        // test
        $this->assertEquals($expectedCode, $response->getContent());
        $this->assertEquals($expectedCode, $response->getStatusCode());
    }


    // public function test404()
    // {
    //     $code = 404;
    //     $route = new \Mwyatt\Core\Route;
    //     $route->appendRoutes('/var/www/html/Framework/tests/Route/definitions.php');
    //     $response = $route->getResponse('fake/');
    //     $this->assertEquals($code, $response->getContent());
    //     $this->assertEquals($code, $response->getStatusCode());
    // }


    // public function test500()
    // {
    //     $code = 500;
    //     $route = new \Mwyatt\Core\Route;
    //     $route->appendRoutes('/var/www/html/Framework/tests/Route/definitions.php');
    //     $response = $route->getResponse('fake/');
    //     $this->assertEquals($code, $response->getContent());
    //     $this->assertEquals($code, $response->getStatusCode());
    // }
}
