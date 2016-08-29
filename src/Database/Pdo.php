<?php

namespace Mwyatt\Core\Database;

/**
 * will act as an interface for any database connection soon
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Pdo implements \Mwyatt\Core\DatabaseInterface
{
   

    /**
     * database handle
     * @var object
     */
    protected $connection;


    /**
     * current prepared statement
     * @var object
     */
    protected $statement;


    public function connect(array $credentials)
    {

        // already connected
        if ($this->connection) {
            return;
        }

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
            $this->connection = new \PDO(
                $dataSourceName,
                $credentials['username'],
                $credentials['password']
            );
        
            // set error mode
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            // $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (\PDOException $exception) {
            throw new \Exception($exception->getMessage());
        }
        return $this->connection;
    }


    public function disconnect()
    {
        return !$this->connection = null;
    }


    public function prepare($sql, $options = [])
    {
        try {
            return $this->statement = $this->connection->prepare($sql, $options);
        } catch (\PDOException $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
    
    
    public function execute($parameters = [])
    {
        try {
            if ($parameters) {
                return $this->statement->execute($parameters);
            } else {
                return $this->statement->execute();
            }
        } catch (\PDOException $exception) {
            throw new \Exception($exception->getMessage());
        }
    }


    public function fetch($mode = \PDO::FETCH_ASSOC, $argument = null)
    {
        try {
            if ($argument) {
                $this->statement->setFetchMode($mode, $argument);
                return $this->statement->fetch();
            } else {
                return $this->statement->fetch($mode);
            }
        } catch (\PDOException $exception) {
            throw new \Exception($exception->getMessage());
        }
    }


    public function fetchAll($mode = \PDO::FETCH_ASSOC, $argument = null)
    {
        try {
            if ($argument) {
                return $this->statement->fetchAll($mode, $argument);
            } else {
                return $this->statement->fetchAll($mode);
            }
        } catch (\PDOException $exception) {
            throw new \Exception($exception->getMessage());
        }
    }


    public function getRowCount()
    {
        try {
            return $this->statement->rowCount();
        } catch (\PDOException $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
    

    public function getLastInsertId($name = null)
    {
        return $this->connection->lastInsertId($name);
    }


    public function bindParam($key, $value, $type = null)
    {
        if ($type) {
            $this->statement->bindParam($key, $value, $type);
        } elseif (is_int($value)) {
            $this->statement->bindParam($key, $value, $this->getParamInt());
        } elseif (is_bool($value)) {
            $this->statement->bindParam($key, $value, $this->getParamBool());
        } elseif (is_null($value)) {
            $this->statement->bindParam($key, $value, $this->getParamNull());
        } elseif (is_string($value)) {
            $this->statement->bindParam($key, $value, $this->getParamStr());
        }
    }


    public function getParamInt()
    {
        return \PDO::PARAM_INT;
    }


    public function getParamNull()
    {
        return \PDO::PARAM_NULL;
    }


    public function getParamStr()
    {
        return \PDO::PARAM_STR;
    }


    public function getParamBool()
    {
        return \PDO::PARAM_BOOL;
    }
}
