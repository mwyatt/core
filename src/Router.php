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
    public function appendRoutes(\Mwyatt\Core\IteratorInterface $routes)
    {
        foreach ($routes as $route) {
            $this->mux->{$route->type}($route->path, [$route->controller, $route->method], $route->options);
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


    public function redirect($url, $statusCode = 302)
    {
        header('location:' . $url, true, $statusCode);
        exit;
    }
}
