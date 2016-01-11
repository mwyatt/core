<?php

namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 */
class Url implements \Mwyatt\Core\UrlInterface, \JsonSerializable
{


    /**
     * /install/directory/
     * static string set in config somewhere
     * @var string
     */
    protected $base;


    /**
     * foo/bar/
     * @var string
     */
    protected $path;


    /**
     * foo=bar&bar=fo
     * @var string
     */
    protected $query;


    /**
     * storage of routes once collected by Route
     * used to build urls later on
     * @var array
     */
    protected $routes = [];


    /**
     * cached representation of the protocol
     * js will review this object so will need to know
     * @var string 'http://'
     */
    protected $protocol;


    /**
     * @param string $host              usually from $_SERVER['HTTP_HOST']
     * @param string $installPathQuery  usually from $_SERVER['REQUEST_URI']
     * @param string $install           foo/bar/
     */
    public function __construct($host, $installPathQuery, $install = '')
    {
        $installPathQueryParts = explode('?', $installPathQuery);

        $host .= '/';

        $query = count($installPathQueryParts) > 1 ? end($installPathQueryParts) : '';

        $installPath = reset($installPathQueryParts);

        $path = str_replace($install, '', ltrim($installPath, '/'));

        $base = $host . $install;

        $this->base = $base;
        $this->path = $path;
        $this->query = $query;
        $this->protocol = $this->getProtocol();
    }


    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }


    /**
     * return an array representation of the query string
     * @return array 
     */
    public function getQueryArray()
    {
        parse_str($this->query, $queryArray);
        return $queryArray;
    }


    /**
     * @return string
     */
    private function getBase()
    {
        return $this->base;
    }


    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }
    

    private function getRoute($key)
    {
        return empty($this->routes[$key]) ? null : $this->routes[$key];
    }

    
    /**
     * store routes found in mux as id => route/:foo/
     * some routes wont have an id as they are post or something
     * these do not need to be stored as they wont need generating
     * @param array $routes
     */
    public function setRoutes(\Pux\Mux $mux)
    {
        foreach ($mux->getRoutes() as $routeMux) {
            if (!empty($routeMux[3]['id'])) {
                $this->routes[$routeMux[3]['id']] = empty($routeMux[3]['pattern']) ? $routeMux[1] : $routeMux[3]['pattern'];
            }
        }
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
    public function generate($key = '', $config = [])
    {

        // home if no key
        if (!$key) {
            return $this->getProtocol() . $this->getBase();
        }

        if (!array_key_exists($key, $this->routes)) {
            throw new \Exception("route '$key' cannot be generated");
        }

        $route = $this->routes[$key];
        $path = ltrim($route, '/');
        foreach ($config as $key => $value) {
            $path = str_replace(':' . $key, $value, $path);
        }
        return $this->getProtocol() . $this->getBase() . $path;
    }


    /**
     * gets absolute path of a single file with cache busting powers!
     * @param  string $path
     * @return string
     */
    public function generateVersioned($pathBase, $pathAppend)
    {
        $pathAbsolute = $pathBase . $pathAppend;
        if (!file_exists($pathAbsolute)) {
            throw new \Exception("cannot get cache busting path for file '$pathAbsolute'");
        }

        // get mod time
        $timeModified = filemtime($pathAbsolute);

        // return url to asset with modified time as query var
        return $this->generate() . $pathAppend . '?' . $timeModified;
    }


    /**
     * face for object when being json encoded
     * @return array 
     */
    public function jsonSerialize() {
        return [
            'base' => $this->getBase(),
            'path' => $this->getPath(),
            'routes' => $this->getRoutes(),
            'protocol' => $this->getProtocol()
        ];
    }
}
