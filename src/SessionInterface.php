<?php

namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
interface SessionInterface
{


    /**
     * extends the normal constructor to set the session data
     */
    public function __construct($scope = '');


    /**
     * @return string
     */
    public function getScope();
    
    
    /**
     * @param string $scope
     */
    public function setScope($scope);


    public function setData($value);


    /**
     * extends getdata from parent
     * @param  string $key
     * @return any
     */
    public function getData();


    /**
     * @return any
     */
    public function getDataKey($key);
    
    
    /**
     * set a key value pair
     * @param string $key
     * @param any $value
     */
    public function setDataKey($key, $value);


    /**
     * pull and unset all data in scope
     * @return mixed
     */
    public function pullData();


    /**
     * gets the value and unsets it
     * @param  string $key
     * @return mixed
     */
    public function pullDataKey($key);
}
