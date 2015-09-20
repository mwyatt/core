<?php

namespace Mwyatt\Core;

/**
 * src: http://avedo.net/101/the-registry-pattern-and-php
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Registry implements RegistryInterface
{


    private $registry = [];


    private static $instance = null;

    
    /**
     * gets a copy of the registry from any scope
     * @return object \Mwyatt\Core\Registry
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Registry;
        }
        return self::$instance;
    }


    /**
     * set a key within the registry
     * @param mixed $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $this->registry[$key] = $value;
    }


    /**
     * get a key within the registry
     * @param  mixed $key
     * @return mixed
     */
    public function get($key)
    {
        return empty($this->registry[$key]) ? null : $this->registry[$key];
    }
}
