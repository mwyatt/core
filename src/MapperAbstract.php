<?php

namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 */
abstract class MapperAbstract
{


    /**
     * the database instance
     * @var object
     */
    protected $database;


    /**
     * the name of table being read
     * @var string
     */
    protected $table;


    /**
     * namespace of the model
     */
    protected $model;


    protected $fetchType = \PDO::FETCH_CLASS;


    public function __construct(\Mwyatt\Core\DatabaseInterface $database)
    {
        $this->database = $database;
        $this->table = strtolower(end(explode('\\', get_class($this))));
        $this->model = '\\Mwyatt\\Core\\Model\\' . end(explode('\\', get_class($this)));
    }


    public function setFetchType($type)
    {
        $this->fetchType = $type;
    }


    public function getIterator($arrayOfObjecta)
    {
        return new \Mwyatt\Core\Utility\ObjectIterator($arrayOfObjecta);
    }


    public function findAll()
    {
        $sql = ['select', '*', 'from', $this->table];

        $this->database->prepare(implode(' ', $sql));
        $this->database->execute();
        
        return $this->getIterator($this->database->fetchAll($this->fetchType, $this->model));
    }


    public function findColumn($values, $column = 'id')
    {
        $results = [];
        $sqlParams = [];

        $sql = ['select', '*', 'from', $this->table, 'where', $column, '='];

        foreach ($values as $value) {
            $sqlParams[] = '?';
        }
        $sql[] = implode(', ', $sqlParams);

        $this->database->prepare(implode(' ', $sql));

        $this->database->execute($values);

        return $this->getIterator($this->database->fetchAll($this->fetchType, $this->model));
    }


    public function insert(array $models)
    {

        // statement
        $statement = [];
        $lastInsertIds = [];
        $statement[] = 'insert into';
        $statement[] = $this->table;
        $statement[] = '(' . $this->getSqlFieldsWriteable() . ')';
        $statement[] = 'values';
        $statement[] = '(' . $this->getSqlPositionalPlaceholdersWriteable() . ')';

        // prepare
        $sth = $this->database->dbh->prepare(implode(' ', $statement));

        // execute
        foreach ($entities as $entity) {
            $sth->execute($this->getSthExecutePositionalWriteable($entity));
            if ($sth->rowCount()) {
                $lastInsertIds[] = intval($this->database->dbh->lastInsertId());
            }
        }

        return $lastInsertIds;
    }
}
