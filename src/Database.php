<?php

namespace Mwyatt\Core;

/**
 * will act as an interface for any database connection soon
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
abstract class Database
{


    /**
     * database handle
     * @var object
     */
    public $dbh;

    
    /**
     * connection credentials
     * @var array
     */
    protected $credentials;
    

    /**
     * @param array $credentials
     */
    public function setCredentials(array $credentials)
    {
        $this->credentials = $credentials;
        return $this;
    }


    protected function validateCredentials(array $expected)
    {
        $expected = [];
        if (!$this->credentials || !\Mwyatt\Core\Helper::arrayKeyExists($expected, $this->credentials)) {
            throw new \Exception('database credentials invalid');
        }
    }
}
