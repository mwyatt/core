<?php

namespace OriginalAppName;

/**
 * will act as an interface for any database connection soon
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Database implements \OriginalAppName\DatabaseInterface
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
        $this->validateCredentials();
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
        if (! \OriginalAppName\Helper::arrayKeyExists($expected, $this->getCredentials())) {
            throw new Exception('database credentials invalid', 3123890);
        }
    }


    /**
     * @return array
     */
    private function getCredentials()
    {
        return $this->credentials;
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
        $credentials = $this->getCredentials();
        try {
            // set data source name
            $dataSourceName = [
                'mysql:host' => $credentials['host'],
                'dbname' => $credentials['basename'],
                'charset' => 'utf8'
            ];
            foreach ($dataSourceName as $key => $value) {
                $dataSourceNameStrings[] = $key . '=' . $value;
            }
            $dataSourceName = implode(';', $dataSourceNameStrings);
            
            // connect
            $this->dbh = new \PDO(
                $dataSourceName,
                $credentials['username'],
                $credentials['password']
            );
        
            // set error mode
            $this->dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $error) {
            return false;
        }
        return $this->dbh;
    }


    public function getLastInsertId()
    {
        return $this->dbh->lastInsertId();
    }
}
