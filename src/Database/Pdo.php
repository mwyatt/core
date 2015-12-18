<?php

namespace Mwyatt\Core\Database;

/**
 * will act as an interface for any database connection soon
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Pdo extends \Mwyatt\Core\Database //implements \Mwyatt\Core\DatabaseInterface
{


    /**
     * current prepared statement
     * @var object
     */
    protected $statement;


    /**
     * the default fetch mode
     * @var int?
     */
    protected $fetchMode = \PDO::FETCH_ASSOC;
   

    public function __construct(array $credentials)
    {
        $this->setCredentials($credentials);
    }


    public function connect()
    {
        if ($this->connection) {
            return;
        }
        $this->validateCredentials([
            'database.host',
            'database.port',
            'database.basename',
            'database.username',
            'database.password'
        ]);
        try {
            // set data source name
            $dataSourceName = [
                'mysql:host' => $this->credentials['database.host'],
                'dbname' => $this->credentials['database.basename'],
                'charset' => 'utf8'
            ];
            foreach ($dataSourceName as $key => $value) {
                $dataSourceNameStrings[] = $key . '=' . $value;
            }
            $dataSourceName = implode(';', $dataSourceNameStrings);
            
            // connect
            $this->connection = new \PDO(
                $dataSourceName,
                $this->credentials['database.username'],
                $this->credentials['database.password']
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
        $this->connection = null;
    }


    public function getStatement()
    {
        if ($this->statement === null) {
            throw new \PDOException("There is no PDOStatement object for use.");
        } 
        return $this->statement;
    }


    public function prepare($sql, $options = [])
    {
        $this->connect();
        try {
            $this->statement = $this->connection->prepare($sql, $options);
            return $this;
        } catch (\PDOException $exception) {
            throw new \RunTimeException($exception->getMessage());
        }
    }
    
    
    public function execute($parameters = [])
    {
        try {
            $this->getStatement()->execute($parameters);
            return $this;
        }
        catch (\PDOException $exception) {
            throw new \RunTimeException($exception->getMessage());
        }
    }


    public function countAffectedRows()
    {
        try {
            return $this->getStatement()->rowCount();
        }
        catch (\PDOException $exception) {
            throw new \RunTimeException($exception->getMessage());
        }
    }
    

    public function getLastInsertId($name = null)
    {
        $this->connect();
        return $this->connection->lastInsertId($name);
    }

    
    public function fetch(
        $fetchStyle = null,
        $cursorOrientation = null,
        $cursorOffset = null
    )
    {
        if ($fetchStyle === null) {
            $fetchStyle = $this->fetchMode;
        }
 
        try {
            return $this->getStatement()->fetch($fetchStyle, 
                $cursorOrientation, $cursorOffset);
        }
        catch (\PDOException $exception) {
            throw new \RunTimeException($exception->getMessage());
        }
    }


    public function fetchAll($fetchStyle = null, $column = 0)
    {
        if ($fetchStyle === null) {
            $fetchStyle = $this->fetchMode;
        }

        try {
            return $fetchStyle === \PDO::FETCH_COLUMN
               ? $this->getStatement()->fetchAll($fetchStyle, $column)
               : $this->getStatement()->fetchAll($fetchStyle);
        }
        catch (\PDOException $exception) {
            throw new \RunTimeException($exception->getMessage());
        }
    }
     
    
    public function select(
        $table,
        $bind = [],
        $boolOperator = "AND"
    ) {
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
            ->countAffectedRows();
    }
    

    public function delete($table, $where = "")
    {
        $sql = "DELETE FROM " . $table . (($where) ? " WHERE " . $where : " ");
        return $this->prepare($sql)
            ->execute()
            ->countAffectedRows();
    }
}
