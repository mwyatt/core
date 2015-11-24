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
     * /install/directory/
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
     * @param string $host             usually from $_SERVER['HTTP_HOST']
     * @param string $request          usually from $_SERVER['REQUEST_URI']
     * @param string $installDirectory foo/bar/
     */
    public function __construct($host, $request, $installDirectory = '')
    {
        $urlServer = strtolower($host . $request);
        $urlParts = explode($installDirectory, $urlServer);
        $base = reset($urlParts) . $installDirectory;
        $path = end($urlParts);
        $this->base = $base;
        $this->path = $path;
        return $this;
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
    private function getBase()
    {
        return $this->base;
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
     * keys all by key so each one must have a key?
     * @param array $routes
     */
    public function setRoutes(array $routes)
    {
        $this->routes = $routes;
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
    public function generate($key = '', $config = [])
    {

        // home if no key
        if (!$key) {
            return $this->getProtocol() . $this->getBase();
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
    public function generateVersioned($pathRelative)
    {
        $pathAbsolute = PATH_BASE . $pathRelative;
        if (!file_exists($pathAbsolute)) {
            throw new \Exception("cannot get cache busting path for file '$pathAbsolute'");
        }

        // get mod time
        $timeModified = filemtime($pathAbsolute);

        // return url to asset with modified time as query var
        return $this->generate() . $pathRelative . '?' . $timeModified;
    }
}
