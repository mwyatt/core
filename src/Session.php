<?php

namespace Mwyatt\Core;

/**
 * session object creates a layer between the $_SESSION variable to
 * help with management of it
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Session extends \Mwyatt\Core\Data implements SessionInterface
{


    protected $scope;


    /**
     * extends the normal constructor to set the session data
     */
    public function __construct($scope = '')
    {
        if ($scope) {
            $this->setScope($scope);
        }
        $this->initialiseData();
    }


    /**
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }
    
    
    /**
     * @param string $scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
        return $this;
    }


    /**
     * initialises the session data into the class data property
     * adds a empty session key array
     */
    private function initialiseData()
    {
        if (! array_key_exists($this->getScope(), $_SESSION)) {
            $_SESSION[$this->getScope()] = [];
        }
    }


    public function setData($value)
    {
        $_SESSION[$this->getScope()] = $value;
        return $this;
    }


    /**
     * excends getdata from parent
     * @param  string $key
     * @return any
     */
    public function getData()
    {
        return $_SESSION[$this->getScope()];
    }


    /**
     * @return any
     */
    public function getDataKey($key)
    {
        if (isset($_SESSION[$this->getScope()][$key])) {
            return $_SESSION[$this->getScope()][$key];
        }
    }
    
    
    /**
     * set a key value pair
     * @param string $key
     * @param any $value
     */
    public function setDataKey($key, $value)
    {
        $_SESSION[$this->getScope()][$key] = $value;
        return $this;
    }


    /**
     * pull and unset all data in scope
     * @return mixed
     */
    public function pullData()
    {
        if (!empty($_SESSION[$this->getScope()])) {
            $data = $_SESSION[$this->getScope()];
            unset($_SESSION[$this->getScope()]);
            return $data;
        }
    }


    /**
     * gets the value and unsets it
     * @param  string $key
     * @return mixed
     */
    public function pullDataKey($key)
    {
        if (!empty($_SESSION[$this->getScope()][$key])) {
            $data = $_SESSION[$this->getScope()][$key];
            unset($_SESSION[$this->getScope()][$key]);
            return $data;
        }
    }
}
