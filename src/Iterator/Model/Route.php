<?php

namespace Mwyatt\Core\Iterator\Model;

class Route extends \Mwyatt\Core\Iterator\Model
{


    public function getByOptionKeyValue($key, $value)
    {
        $routes = [];
        foreach ($this as $route) {
            if (isset($route->options[$key]) && $route->options[$key] === $value) {
                $routes[] = $route;
            }
        }
        return new $this($routes);
    }
}
