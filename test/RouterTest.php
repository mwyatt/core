<?php
namespace Mwyatt\Core;

class RouterTest extends \PHPUnit_Framework_TestCase
{


    public function testGetResponse()
    {
        $router = new \Mwyatt\Core\Router(new \Pux\Mux);
        $router->mux->get('/', ['\\PackageName\\Controller\\Index', 'home'], ['id' => 'home']);
        $router->mux->get('/product/:name/:id/', ['\\PackageName\\Controller\\Index', 'product'], ['id' => 'product.single']);
        $router->mux->post('/product/:name/:id/', ['\\PackageName\\Controller\\Index', 'product'], ['id' => 'product.single']);
        $subMux = new \Pux\Mux;
        $subMux->get('/bar/', ['\\PackageName\\Controller\\Index', 'bar'], ['id' => 'foo.bar']);
        $subMux->get('/bar/do/', ['\\PackageName\\Controller\\Index', 'bar'], ['id' => 'foo.bar.do']);
        $router->mux->mount('/foo', $subMux);
        $router->mux->get('/asset/:path', ['\\PackageName\\Controller\\Index', 'asset'], ['id' => 'asset.single', 'require' => ['path' => '.+']]);

        $route = $mux->dispatch('/asset/ok/something.jpg');

        // auth
        $path = empty($route[3]['pattern']) ? $route[1] : $route[3]['pattern'];
        if (strpos($path, 'admin/')) {
            echo '<pre>';
            print_r('do auth');
            echo '</pre>';
        }

        $response = \Pux\Executor::execute($route);
        echo '<pre>';
        print_r($response);
        echo '</pre>';
        exit;





        $url = new \Mwyatt\Core\Url('192.168.1.24', '/core/foo/bar/?foo=bar', 'core/');
        $this->assertEquals('foo/bar/?foo=bar', $url->getPath());


        $installDir = 'sandbox/aura-router/';
        include 'vendor/autoload.php';
        $router_factory = new \Aura\Router\RouterFactory;
        $router = $router_factory->newInstance();

        // add a simple named route without params
        $router
            ->add('home', '/')
            ->setValues(array(
                'controller' => 'Index',
                'method' => 'home'
            ));

        $router
            ->add('foo.bar-tree', '/product/{id}/')
            ->setValues(array(
                'controller' => 'Index',
                'method' => 'foo'
            ));

        // get the incoming request URL path
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if (!empty($installDir)) {
            $path = str_replace($installDir, '', $path);
        }

        // get the route based on the path and server
        if (!$route = $router->match($path, $_SERVER)) {
            // 404
            
        }

        $controller = '\\OriginalThing\\Controller\\' . $route->params['controller'];
        $controller = new $controller;
        $controller->{$route->params['method']}($route->params);





\\Mwyatt\\Core\\Controller\\
        
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
