<?php
namespace Mwyatt\Core;

class RouterTest extends \PHPUnit_Framework_TestCase
{


    public function testGetSimple()
    {
        $view = new \Mwyatt\Core\View;
        $router = new \Mwyatt\Core\Router(new \Pux\Mux);
        $request = new \Mwyatt\Core\Request();

        $routes = array_merge(
            include $view->getPathBasePackage('routes.php')
        );
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
