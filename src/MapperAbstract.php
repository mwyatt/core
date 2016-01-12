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
    }


    public function fetchAll($type = \PDO::FETCH_CLASS)
    {
        $queryBuilder = $this->database->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from($this::TABLE);
        $statement = $this->database->prepare($queryBuilder->getSQL());
        $statement->execute();
        $statement->fetchAll($type, $this::MODEL);
    }


    public function fetchColumn($values, $column = 'id')
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
}
