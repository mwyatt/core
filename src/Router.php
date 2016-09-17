<?php

namespace Mwyatt\Core;

class Router implements \Mwyatt\Core\RouterInterface
{
    private $mux;
    private $muxRouteCurrent;


    public function __construct(\Pux\Mux $mux)
    {
        $this->mux = $mux;
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
        $path = '/' . $path;
        $path = str_replace('//', '/', $path);
        $route = $this->mux->dispatch($path);
        $this->muxRouteCurrent = $route;
        return $route;
    }


    public function getMuxRouteCurrentController()
    {
        if (!$this->muxRouteCurrent) {
            throw new \Exception('No mux route has been chosen yet.');
        }
        return isset($this->muxRouteCurrent[2][0]) ? $this->muxRouteCurrent[2][0] : '';
    }


    public function getMuxRouteCurrentControllerMethod()
    {
        if (!$this->muxRouteCurrent) {
            throw new \Exception('No mux route has been chosen yet.');
        }
        return isset($this->muxRouteCurrent[2][1]) ? $this->muxRouteCurrent[2][1] : '';
    }


    public function executeRoute(array $route)
    {
        return \Pux\Executor::execute($route);
    }


    /**
     * just response code for now
     * is the response was more detailed this could be setup further
     */
    public function setHeaders(\Mwyatt\Core\ResponseInterface $response)
    {
        http_response_code($response->getStatusCode());
    }


    public function getMux()
    {
        return $this->mux;
    }
}
