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


    abstract protected function getEntity(array $row);


    public function getDatabase()
    {
        return $this->database;
    }


    /**
     * @return string
     */
    public function getTableName()
    {
        if ($this->tableName) {
            return $this->tableName;
        }
    }


    public function findById($id)
    {
        $this->database->select($this->getTableName(), ['id' => $id]);
        if (!$row = $this->database->fetch()) {
            return null;
        }
        return $this->getEntity($row);
    }


    public function findAll($conditions = [])
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
