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
    const TABLE = 'foo';


    /**
     * namespace of the model
     */
    const MODEL = '\\Mwyatt\\Core\\Model\\Foo';


    public function __construct(\Mwyatt\Core\DatabaseInterface $database)
    {
        $this->database = $database;
        $this::TABLE = strtolower($this::getClass());
        $this::MODEL = '\\Mwyatt\\Core\\Model\\' . $this::getClass();
    }


    public function findAll($type = \PDO::FETCH_CLASS)
    {
        $queryBuilder = $this->database->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from($this::TABLE);
        $statement = $this->database->prepare($queryBuilder->getSQL());
        $statement->execute();
        $statement->fetchAll($type, $this::MODEL);
    }


    public function findColumn($values, $column = 'id')
    {
        $queryBuilder = $this->database->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from($this::TABLE)
            ->where("$column = ?")
            ->setParameter(0, $values);
        $statement = $this->database->prepare($queryBuilder->getSQL());
        $statement->execute();
        $statement->fetchAll($type, $this::MODEL);
    }


    public function insert(array $models)
    {

        // statement
        $statement = [];
        $lastInsertIds = [];
        $statement[] = 'insert into';
        $statement[] = $this::TABLE;
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

        return $$lastInsertIds;
    }


    public function delete(array $models, $column = 'id')
    {

    }
}
