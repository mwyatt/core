<?php

namespace Mwyatt\Core\Database;

/**
 * will act as an interface for any database connection soon
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Pdo extends \Mwyatt\Core\Database implements \Mwyatt\Core\DatabaseInterface
{

    
    public function connect(array $credentials)
    {
        $this->setCredentials($credentials);
        $this->validateCredentials([
            'host',
            'port',
            'basename',
            'username',
            'password'
        ]);
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
        } catch (\PDOException $exception) {
            throw new \Exception($exception->getMessage());
        }
        return $this->dbh;
    }
}
