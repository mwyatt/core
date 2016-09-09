<?php

namespace Mwyatt\Core\Database;

class Mock implements \Mwyatt\Core\DatabaseInterface
{
   

    protected $connection;
    protected $statement;


    public function connect(array $credentials)
    {
        return true;
    }


    public function disconnect()
    {
        return true;
    }


    public function prepare($sql, $options = [])
    {
        return $this->statement = true;
    }
    
    
    public function execute($parameters = [])
    {
        return true;
    }


    public function fetch($mode = \PDO::FETCH_ASSOC, $argument = null)
    {
        return ['id' => 10, 'email' => ''];
        // if ($argument) {
        //     $this->statement->setFetchMode($mode, $argument);
        //     return $this->statement->fetch();
        // } else {
        //     return $this->statement->fetch($mode);
        // }
    }


    public function fetchAll($mode = \PDO::FETCH_ASSOC, $argument = null)
    {
        return [$this->fetch()];
    }


    public function getRowCount()
    {
        return 1;
    }
    

    public function getLastInsertId($name = null)
    {
        return 10;
    }


    public function beginTransaction()
    {
        return true;
    }


    public function rollback()
    {
        return true;
    }


    public function commit()
    {
        return true;
    }


    public function bindParam($key, $value, $type = null)
    {
        $this->statement->bindParam($key, $value, $type);
    }


    public function getFetchTypeAssoc()
    {
        return \PDO::FETCH_ASSOC;
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
