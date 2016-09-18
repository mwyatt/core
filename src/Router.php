<?php

namespace Mwyatt\Core;

class Router implements \Mwyatt\Core\RouterInterface
{
    private $mux;


    public function __construct(\Pux\Mux $mux)
    {
        $this->mux = $mux;
    }


    /**
     * stores array of routes into mux, must have the correct
     * numerical keys
     * @param  array  $routes
     */
    public function appendRoutes(array $routes)
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
     * find the right route using the path '/foo/bar/'
     * must us trailing slash
     */
    public function getMatch($path)
    {
        return $this->mux->dispatch($path);
    }


    public function getRouteControllerName(array $route)
    {
        return isset($route[2][0]) ? $route[2][0] : '';
    }


    public function getRouteControllerMethod(array $route)
    {
        return isset($route[2][1]) ? $route[2][1] : '';
    }


    /**
     * just response code for now
     * is the response was more detailed this could be setup further
     */
    public function setHeaders(\Mwyatt\Core\ResponseInterface $response)
    {
        http_response_code($response->getStatusCode());
    }
}
