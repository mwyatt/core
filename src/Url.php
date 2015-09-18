<?php

namespace Mwyatt\Core;

/**
 * the aim of this class is to do all things url
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Url implements \Mwyatt\Core\UrlInterface
{


    /**
     * foo.co.uk/install/directory/
     * static string set in config somewhere
     * @var string
     */
    public $base;


    /**
     * foo/bar/
     * @var string
     */
    public $path;


    /**
     * storage of routes once collected by Route
     * used to build urls later on
     * @var array
     */
    public $routes;


    /**
     * @param string $base host.com/install/directory/
     */
    public function __construct($base)
    {
        $this->base = $base;
        $this->setPath();
    }


    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }


    /**
     * @return string
     */
    public function getBase()
    {
        return $this->base;
    }


    private function setPath()
    {
        foreach (['HTTP_HOST', 'REQUEST_URI'] as $key) {
            if (empty($_SERVER[$key])) {
                throw new \Exception('class url requires "_SERVER" key "$key" to be accessible and filled');
            }
        }
        $this->path = str_replace($this->base, '', strtolower($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
        return $this;
    }


    /**
     * @return array
     */
    private function getRoutes()
    {
        return $this->routes;
    }
    

    private function getRoute($key)
    {
        return empty($this->routes[$key]) ? null : $this->routes[$key];
    }

    
    /**
     * store routes in the class for use with generate
     * keys all by key
     * @param array $routes
     */
    public function setRoutes(array $routes)
    {
        $routesByKey = [];
        foreach ($routes as $route) {
            $routesByKey[$route->key] = $route;
        }
        $this->routes = $routesByKey;
        return $this;
    }


    /**
     * http(s)://
     * @return string
     */
    private function getProtocol($secure = false)
    {
        $protocol = 'http';
        if ($secure || $this->isSecure()) {
            $protocol .= 's';
        }
        return  $protocol . ':' . '/' . '/';
    }


    /**
     * checks to see if the current connection is secure
     * checks server vars and server port, untested
     * @return boolean
     */
    private function isSecure()
    {
        if (empty($_SERVER['SERVER_PORT']) || empty($_SERVER['HTTPS'])) {
            return;
        }
        return (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
    }



    /**
     * builds a url based on the plans stored
     * swap :key for $config[$key]
     * @param  string $key
     * @param  array $config key, value
     * @return string         url/path/
     */
    public function generate($key = 'home', $config = [], $absolute = true)
    {
        $route = $this->getRoute($key);
        $path = ltrim($route->path, '/');
        foreach ($config as $key => $value) {
            $path = str_replace(':' . $key, $value, $path);
        }
        return $this->getProtocol() . $this->getBase() . $path;
    }
}
