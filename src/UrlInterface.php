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
     * @param string $base host.com/install/directory/
     */
    public function __construct($base);


    /**
     * @return string
     */
    public function getBase();


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
}
