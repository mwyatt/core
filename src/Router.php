<?php

namespace Mwyatt\Core;

class Router implements \Mwyatt\Core\RouterInterface
{
    private $mux;
    private $routes;


    public function __construct(
        \Pux\Mux $mux,
        array $routes = []
    ) {
        $this->mux = $mux;
        $this->setRoutesAsObjects($routes);
        $this->setRoutesInMux($this->routes);
    }


    private function setRoutesAsObjects(array $routes)
    {
        $routeOs = [];
        foreach ($routes as $route) {
            $routeO = new \Mwyatt\Core\Route;
            $routeO->type = $route[0];
            $routeO->path = $route[1];
            $routeO->controller = $route[2];
            $routeO->method = $route[3];
            $routeO->options = isset($route[4]) ? $route[4] : [];
            $routeOs[] = $routeO;
        }
        $routeIterator = new \Mwyatt\Core\Iterator\Model\Route($routeOs);
        foreach ($routeIterator as $route) {
            if (!$route->getOption('id')) {
                throw new \Exception("Option 'id' not set for route with path '{$route->path}'.");
            }
        }
        $this->routes = $routeIterator;
    }


    public function getRoutes()
    {
        return $this->routes;
    }


    private function setRoutesInMux(\Mwyatt\Core\Iterator\Model\Route $routes)
    {
        foreach ($routes as $route) {
            $this->mux->{$route->type}($route->path, [$route->controller, $route->method], $route->options);
        }
    }


    /**
     * find the right route using the path '/foo/bar/'
     * must use trailing slash /example/thing
     * store routematch as object
     */
    public function getMatch($path)
    {
        $routeMatch = $this->mux->dispatch($path);
        if ($routeMatch) {
            $routes = $this->routes->getByOptionKeyValue('id', isset($routeMatch[3]['id']) ? $routeMatch[3]['id'] : '');
            if ($route = $routes->getFirst()) {
                $route->pathVars = isset($routeMatch[3]['vars']) ? $routeMatch[3]['vars'] : [];
            }
            return $route;
        }
    }


    public function redirect($url, $statusCode = 302)
    {
        header('location:' . $url, true, $statusCode);
        exit;
    }
}
