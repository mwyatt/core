<?php

namespace Mwyatt\Core;

/**
 * will act as an interface for any database connection soon
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Pdo implements \Mwyatt\Core\Database
{


    /**
     * PDO object once connected
     * @var object
     */
    public $dbh;

    
    /**
     * connection credentials
     * @var array
     */
    private $credentials;


    /**
     * connects to the database
     */
    public function __construct($credentials)
    {
        $this->setCredentials($credentials);
        $this->validateCredentials([
            'host',
            'port',
            'basename',
            'username',
            'password'
        ]);
        $this->connect();
    }
    

    private function validateCredentials()
    {
        $expected = [
            'host',
            'port',
            'basename',
            'username',
            'password'
        ];
        if (! \Mwyatt\Core\Helper::arrayKeyExists($expected, $this->credentials)) {
            throw new Exception('database credentials invalid', 3123890);
        }
    }
    
    
    /**
     * @param array $credentials
     */
    private function setCredentials($credentials)
    {
        $this->credentials = $credentials;
        return $this;
    }
    
    
    public function connect()
    {
        try {
            // set data source name
            $dataSourceName = [
                'mysql:host' => $this->credentials['host'],
                'dbname' => $this->credentials['basename'],
                'charset' => 'utf8'
            ];
            foreach ($dataSourceName as $key => $value) {
                $dataSourceNameStrings[] = $key . '=' . $value;
            }
            $dataSourceName = implode(';', $dataSourceNameStrings);
            
            // connect
            $this->dbh = new \PDO(
                $dataSourceName,
                $this->credentials['username'],
                $this->credentials['password']
            );
        
            // set error mode
            $this->dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $error) {
            return false;
        }
        return $this->dbh;
    }
}
