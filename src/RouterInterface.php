<?php

namespace Mwyatt\Core;

interface RouterInterface
{
    public function __construct(\Pux\Mux $mux);
    public function appendRoutes(array $routes);
    public function getMatch($path);
    public function getRouteControllerName(array $route);
    public function getRouteControllerMethod(array $route);
}
