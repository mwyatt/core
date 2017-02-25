<?php

namespace Mwyatt\Core;

/**
 * central hub for all url creation and manipulation
 * requires routes so that it can generate urls using that layout
 */
class Url implements \Mwyatt\Core\UrlInterface, \JsonSerializable
{
    protected $protocol;
    protected $base;
    protected $path;
    protected $query;
    protected $routes = [];


    /**
     * @param string $host              usually from $_SERVER['HTTP_HOST']
     * @param string $installPathQuery  usually from $_SERVER['REQUEST_URI']
     * @param string $install           foo/bar/
     */
    public function __construct(
        $host,
        $installPathQuery,
        $install = ''
    ) {
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
     * builds a url based on the plans stored
     * swap :key for $config[$key]
     * @param  string $key
     * @param  array $config key, value
     * @param  array $query key, value
     * @return string         url/path/
     */
    public function generate($key = '', $config = [], array $query = [])
    {
        $queryString = '';
        if (!$key) {
            return $this->protocol . $this->base;
        }
        if (!$route = $this->getRoute($key)) {
            throw new \Exception("route '$key' cannot be generated");
        }
        $path = ltrim($route, '/');
        foreach ($config as $key => $value) {
            $path = str_replace(':' . $key, $value, $path);
        }
        if ($query) {
            $queryString = '?' . http_build_query($query);
        }
        return $this->protocol . $this->base . $path . $queryString;
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


    private function getRoute($key)
    {
        return isset($this->routes[$key]) ? $this->routes[$key] : null;
    }


    /**
     * store routes as id => route/:foo/
     * some routes wont have an id as they are post or something
     * these do not need to be stored as they wont need generating
     * @param array $routes
     */
    public function setRoutes(\Mwyatt\Core\IteratorInterface $routes)
    {
        foreach ($routes as $route) {
            $id = $route->getOption('id');
            if ($id) {
                $this->routes[$id] = $route->path;
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


    public function jsonSerialize()
    {
        return [
            'base' => $this->base,
            'path' => $this->path,
            'routes' => $this->routes,
            'protocol' => $this->protocol
        ];
    }
}
