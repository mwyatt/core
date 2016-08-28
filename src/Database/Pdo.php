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
            return $this->statement->execute($parameters);
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
}


/*

    public function select($sql) {
    
        if ($bind) {
            $where = [];
            foreach ($bind as $col => $value) {
                unset($bind[$col]);
                $bind[":" . $col] = $value;
                $where[] = $col . " = :" . $col;
            }
        }

        $sql = "SELECT * FROM " . $table
            . (($bind) ? " WHERE "
            . implode(" " . $boolOperator . " ", $where) : " ");
        $this->prepare($sql)
            ->execute($bind);
        return $this;
    }

    
    public function insert($table, array $bind)
    {
        $cols = implode(", ", array_keys($bind));
        $values = implode(", :", array_keys($bind));
        foreach ($bind as $col => $value) {
            unset($bind[$col]);
            $bind[":" . $col] = $value;
        }
 
        $sql = "INSERT INTO " . $table
            . " (" . $cols . ")  VALUES (:" . $values . ")";
        return (int) $this->prepare($sql)
            ->execute($bind)
            ->getLastInsertId();
    }


    public function update($table, array $bind, $where = "")
    {
        $set = [];
        foreach ($bind as $col => $value) {
            unset($bind[$col]);
            $bind[":" . $col] = $value;
            $set[] = $col . " = :" . $col;
        }
 
        $sql = "UPDATE " . $table . " SET " . implode(", ", $set)
            . (($where) ? " WHERE " . $where : " ");
        return $this->prepare($sql)
            ->execute($bind)
            ->getRowCount();
    }
    

    public function delete($table, $where = "")
    {
        $sql = "DELETE FROM " . $table . (($where) ? " WHERE " . $where : " ");
        return $this->prepare($sql)
            ->execute()
            ->getRowCount();
    }

 */
