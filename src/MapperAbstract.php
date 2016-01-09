<?php

namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
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
    protected $tableName;


    public function __construct(\Mwyatt\Core\DatabaseInterface $database)
    {
        $this->database = $database;
    }


    public function getDatabase()
    {
        return $this->database;
    }


    public function getTableName()
    {
        return $this->tableName;
    }


    public function selectAll($conditions = [])
    {
        $entities = [];
        $this->database->select($this->getTableName(), $conditions);
        $rows = $this->database->fetchAll();

        if ($rows) {
            foreach ($rows as $row) {
                $entities[] = $this->getEntity($row);
            }
        }

        return $entities;
    }
}
