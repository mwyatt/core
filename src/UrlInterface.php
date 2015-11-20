<?php

namespace Mwyatt\Core;

/**
 * the aim of this class is to do all things url
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
interface UrlInterface
{


    /**
     * @param string $host             usually from $_SERVER['HTTP_HOST']
     * @param string $request          usually from $_SERVER['REQUEST_URI']
     * @param string $installDirectory foo/bar/
     */
    public function __construct($host, $request, $installDirectory = '');


    /**
     * @return string
     */
    public function getPath();

    
    /**
     * store routes in the class for use with generate
     * @param array $routes
     */
    public function setRoutes(array $routes);


    /**
     * builds a url based on the plans stored
     * swap :key for $config[$key]
     * @param  string $key
     * @param  array $config key, value
     * @return string         url/path/
     */
    public function generate($key = 'home', $config = []);


    /**
     * gets absolute path of a single file with cache busting powers!
     * @param  string $path
     * @return string
     */
    public function generateVersioned($pathRelative);
}
