<?php
namespace Mwyatt\Core;

class RouterTest extends \PHPUnit_Framework_TestCase
{


    public function testGetResponseSimple()
    {
        $url = new \Mwyatt\Core\Url('192.168.1.24/', '/core/', 'core/');
        $router = new \Mwyatt\Core\Router(new \Pux\Mux);
        $router->appendMuxRoutes(
            ['routes.php'],
            new \Mwyatt\Core\Database,
            new \Mwyatt\Core\View($url),
            $url
        );
        $route = $router->getRoute($url->getPath());
        $response = $router->executeRoute($route);
        $this->assertEquals('testSimple', $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }


    public function testGetResponseParams()
    {
        $url = new \Mwyatt\Core\Url('192.168.1.24/', '/core/product/foo-bar/1/', 'core/');
        $router = new \Mwyatt\Core\Router(new \Pux\Mux);
        $router->appendMuxRoutes(
            ['routes.php'],
            new \Mwyatt\Core\Database,
            new \Mwyatt\Core\View($url),
            $url
        );
        $route = $router->getRoute($url->getPath());
        $response = $router->executeRoute($route);
        $this->assertEquals('testParams, foo-bar, 1', $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }


    public function testErrorNotFound()
    {
        $url = new \Mwyatt\Core\Url('192.168.1.24/', '/core/not-found/', 'core/');
        $router = new \Mwyatt\Core\Router(new \Pux\Mux);
        $router->appendMuxRoutes(
            ['routes.php'],
            new \Mwyatt\Core\Database,
            new \Mwyatt\Core\View($url),
            $url
        );
        $route = $router->getRoute($url->getPath());
        $this->assertEquals(null, $route);
    }
}
