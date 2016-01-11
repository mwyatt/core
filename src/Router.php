<?php
namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Router //implements \Mwyatt\Core\RouterInterface
{


    private $controllerNamespace = '\\Mwyatt\\Core\\Controller\\';


    /**
     * \Pux\Mux
     * @var object
     */
    public $mux;


    private $muxRouteCurrent;


    /**
     * init mux
     */
    public function __construct(\Pux\Mux $mux)
    {
        $this->mux = $mux;
    }


    public function getMux()
    {
        return $this->mux;
    }


    /**
     * stores array of routes into mux, must have the correct
     * numerical keys
     * @param  array  $routes
     */
    public function appendMuxRoutes(array $routes)
    {
        foreach ($routes as $route) {
            $requestType = $route[0];
            $urlPath = $route[1];
            $controller = $route[2];
            $method = $route[3];
            $options = empty($route[4]) ? [] : $route[4];
            
            $this->mux->$requestType($urlPath, [$controller, $method], $options);
        }
    }


    /**
     * find the right route using the path 'foo/bar/'
     * store as current one
     */
    public function getMuxRouteCurrent($path)
    {
        $route = $this->mux->dispatch($path);
        $this->setMuxRouteCurrent($route);
        return $route;
    }


    public function setMuxRouteCurrent($route)
    {
        $this->muxRouteCurrent = $route;
    }


    public function getMuxRouteCurrentController()
    {
        return $this->muxRouteCurrent[2][0];
    }


    public function getMuxRouteCurrentControllerMethod()
    {
        return $this->muxRouteCurrent[2][1];
    }


    public function executeRoute(array $route)
    {
        return \Pux\Executor::execute($route);
    }


    /**
     * return a key > path pair for use when generating urls
     * \Mwyatt\Core\Url
     * @return array
     */
    public function getUrlRoutes()
    {
        $response = [];
        foreach ($this->mux->getRoutes() as $route) {
            $response[$route[3]['id']] = empty($route[3]['pattern']) ? $route[1] : $route[3]['pattern'];
        }
        return $response;
    }


    /**
     * obtain response
     * perhaps store a base controller so that you are able to control
     * the 404 and 500 responses?
     * currently unused here, will be used in a abstracted dispatch file
     * @return object Response
     */
    private function dispatchRoute($route)
    {
        $route = $this->mux->dispatch($path);
        $response = new \Mwyatt\Core\Response('');

        // found route
        if ($route) {
            // do controller->method
            try {
                $response = \Pux\Executor::execute($route);

            // 500 - unexpected error
            } catch (\Exception $exception) {
                $response = $controller->e500($exception);
            }

        // 404 - not found
        } else {
            $response->setStatusCode(404);
        }

        // 404 content, could have been defined in a found route
        if ($response->getStatusCode() == 404) {
            $response = $controller->e404($response);
        }

        // the response
        return $response;
    }


    /**
     * just response code for now
     */
    public function setHeaders(\Mwyatt\Core\ResponseInterface $response)
    {
        http_response_code($response->getStatusCode());
    }
}
