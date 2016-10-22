<?php

namespace Mwyatt\Core\Database;

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


    public function connect(
        $host,
        $basename,
        $username,
        $password
    )
    {

        // already connected
        if ($this->connection) {
            return;
        }

        try {
            // set data source name
            $dataSourceName = [
                'mysql:host' => $host,
                'dbname' => $basename,
                'charset' => 'utf8'
            ];
            foreach ($dataSourceName as $key => $value) {
                $dataSourceNameStrings[] = $key . '=' . $value;
            }
            $dataSourceName = implode(';', $dataSourceNameStrings);
            
            // connect
            $this->connection = new \PDO(
                $dataSourceName,
                $username,
                $password
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
        return $this->statement = $this->connection->prepare($sql, $options);
    }
    
    
    public function execute($parameters = [])
    {
        if ($parameters) {
            return $this->statement->execute($parameters);
        } else {
            return $this->statement->execute();
        }
    }


    public function fetch($mode = \PDO::FETCH_ASSOC, $argument = null)
    {
        if ($argument) {
            $this->statement->setFetchMode($mode, $argument);
            return $this->statement->fetch();
        } else {
            return $this->statement->fetch($mode);
        }
    }


    public function fetchAll($mode = \PDO::FETCH_ASSOC, $argument = null)
    {
        if ($argument) {
            return $this->statement->fetchAll($mode, $argument);
        } else {
            return $this->statement->fetchAll($mode);
        }
    }


    public function getRowCount()
    {
        return $this->statement->rowCount();
    }
    

    public function getLastInsertId($name = null)
    {
        return $this->connection->lastInsertId($name);
    }


    public function beginTransaction()
    {
        return $this->connection->beginTransaction();
    }


    public function rollback()
    {
        return $this->connection->rollback();
    }


    public function commit()
    {
        return $this->connection->commit();
    }


    public function bindParam($key, $value, $type = null)
    {
        $this->statement->bindParam($key, $value, $type);
    }


    public function getFetchTypeAssoc()
    {
        return \PDO::FETCH_ASSOC;
    }


    public function getFetchTypeClass()
    {
        return \PDO::FETCH_CLASS;
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
