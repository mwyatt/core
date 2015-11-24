<?php
namespace Mwyatt\Core;

class RouterTest extends \PHPUnit_Framework_TestCase
{


    public function testGetResponse()
    {
        $url = new \Mwyatt\Core\Url('192.168.1.24/', '/core/product/name/1/', 'core/');
        $router = new \Mwyatt\Core\Router(new \Pux\Mux);
        $router->appendMuxRoutes(['routes.php']);
        echo '<pre>';
        print_r($router->mux);
        print_r('/' . $url->getPath());
        echo '</pre>';
        exit;
        
        $route = $router->getRoute('/' . $url->getPath());
        if (!$route) {
            echo '<pre>';
            print_r('404');
            echo '</pre>';
            exit;
            
        }
        $response = $router->executeRoute($route);









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
