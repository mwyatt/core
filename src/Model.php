<?php

namespace Mwyatt\Core;

/**
 * responses
 *     create
 *         id from created row
 *     read
 *         the rows received
 *     update
 *         count of affected rows
 *     delete
 *         count of affected rows
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
abstract class Model extends \Mwyatt\Core\Data
{


    /**
     * the database instance
     * @var object
     */
    public $database;


    /**
     * the name of table being read
     * @var string
     */
    public $tableName;


    /**
     * identifies which object to store results in from a read
     * @var string
     */
    public $entity = '\\Mwyatt\\Core\\Entity\\Foo';


    /**
     * comprehensive list of database fields for use when building queries
     * lazily
     * @var array
     */
    public $fields = [];


    /**
     * always array
     * @var array
     */
    public $data;


    /**
     * inject dependencies
     */
    public function __construct($database = null)
    {

        // already added
        if ($database) {
            return $this->setDatabase($database);
        }
        $registry = \Mwyatt\Core\Registry::getInstance();

        // if not in registry connect + create
        if (! $database = $registry->get('database')) {
            $database = new \Mwyatt\Core\Database\Pdo(include BASE_PATH . 'credentials' . EXT);
            $registry->set('database', $database);
        }
        $this->database = $database;
    }


    /**
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }
    
    
    /**
     * @param string $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }


    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }


    /**
     * data = ids created [1, 5, 2]
     * @param  array $entities
     * @return object
     */
    public function create(Array $entities)
    {

        // statement
        $statement = [];
        $createdIds = [];
        $statement[] = 'insert into';
        $statement[] = $this->getTableName();
        $statement[] = '(' . $this->getSqlFields() . ')';
        $statement[] = 'values';
        $statement[] = '(' . $this->getSqlPositionalPlaceholders() . ')';

        // prepare
        $sth = $this->database->dbh->prepare(implode(' ', $statement));

        // execute
        foreach ($entities as $entity) {
            $sth->execute($this->getSthExecutePositional($entity));
            if ($sth->rowCount()) {
                $createdIds[] = intval($this->database->dbh->lastInsertId());
            }
        }

        // return
        $this->setData($createdIds);
        return $this;
    }


    /**
     * reads everything
     * data = array of entities
     * @return object
     */
    public function read()
    {

        // query
        $sth = $this->database->dbh->prepare("
            {$this->getSqlSelect()}
            where id != 0
		");

        // mode
        $sth->setFetchMode(\PDO::FETCH_CLASS, $this->getEntity());

        // execute
        $sth->execute();

        // fetch
        $this->setData($sth->fetchAll());

        // instance
        return $this;
    }


    /**
     * global read for ids
     * extend this if more detail required
     * @param  array $ids
     * @return object
     */
    public function readId($ids, $column = 'id')
    {

        // query
        $sth = $this->database->dbh->prepare("
            {$this->getSqlSelect()}
            where {$column} = :id
        ");

        $entity = $this->getEntity();

        // mode
        $sth->setFetchMode(\PDO::FETCH_CLASS, $this->getEntity());

        // loop prepared statement
        foreach ($ids as $id) {
            $sth->bindValue(':id', $id, \PDO::PARAM_INT);
            $sth->execute();
            while ($result = $sth->fetch()) {
                $this->appendData($result);
            }
        }

        // instance
        return $this;
    }


    /**
     * reads where column == value
     * @param  string $column table column
     * @param  any $value  to match
     * @return object
     */
    public function readColumn($column, $value)
    {

        // query
        $sth = $this->database->dbh->prepare("
            {$this->getSqlSelect()}
            where {$column} = :value
        ");

        // mode
        $sth->setFetchMode(\PDO::FETCH_CLASS, $this->getEntity());
        $this->bindValue($sth, ':value', $value);
        $sth->execute();
        $this->setData($sth->fetchAll());

        // instance
        return $this;
    }


    /**
     * uses the passed properties to build named prepared statement
     * maintiaining this: public function update($mold, $where = [])
     * data = array of id => status [1 => 1, 2 => 0]
     * @todo how to return a value which can mark success?
     * @param  array  $molds
     * @param  string $by    defines the column to update by
     * @return int
     */
    public function update(Array $entities, $column = 'id')
    {
        $updatedCount = 0;

        // statement
        $statement = [];
        $statement[] = 'update';
        $statement[] = $this->getTableName();
        $statement[] = 'set';

        // must be writable columns
        $named = [];
        foreach ($this->getFields() as $field) {
            $named[] = $field . ' = :' . $field;
        }
        $statement[] = implode(', ', $named);
        $statement[] = "where $column = :column";

        // prepare
        $sth = $this->database->dbh->prepare(implode(' ', $statement));

        // execute
        foreach ($entities as $entity) {
            foreach ($this->getSthExecuteNamed($entity) as $key => $value) {
                $this->bindValue($sth, $key, $value);
            }
            $this->bindValue($sth, 'column', $entity->$column);
            $sth->setFetchMode(\PDO::FETCH_CLASS, $this->getEntity());
            $sth->execute();
            $updatedCount += $sth->rowCount();
        }
        $this->setRowCount($updatedCount);
        return $this;
    }


    /**
     * uses where property to build delete statement
     * improved to allow entities to be passed
     * data = array of id => status [1 => 1, 2 => 0]
     * @param  array  $properties
     * @return int
     */
    public function delete(Array $entities, $column = 'id')
    {

        // build
        $rowCount = 0;
        $statement = [];
        $statement[] = 'delete from';
        $statement[] = $this->getTableName();
        $statement[] = "where $column = ?";

        // prepare
        $sth = $this->database->dbh->prepare(implode(' ', $statement));

        // bind
        foreach ($entities as $entity) {
            $this->bindValue($sth, 1, $entity->$column);
            $sth->execute();
            $rowCount += $sth->rowCount();
        }

        // return
        $this->setRowCount($rowCount);
        return $this;
    }


    /**
     * builds a generic select statement and returns
     * select (column, column) from (table_name)
     * @return string
     */
    public function getSqlSelect()
    {
        $statement = [];
        $statement[] = 'select';
        $statement[] = $this->getSqlFields();
        $statement[] = 'from';
        $statement[] = $this->getTableName();
        return implode(' ', $statement);
    }


    /**
     * implodes list of sql fields
     * column, column, column
     * @return string
     */
    public function getSqlFields()
    {
        return implode(', ', $this->fields);
    }


    /**
     * @return string ?, ?, ? of all fields
     */
    public function getSqlPositionalPlaceholders()
    {
        $placeholders = [];
        foreach ($this->fields as $field) {
            $placeholders[] = '?';
        }
        return implode(', ', $placeholders);
    }


    /**
     * uses a entity to build sth execute data
     * if 'time' involved assume that time needs to be inserted, could be
     * a bad idea
     * @param  object $entity instance of entity
     * @return array
     */
    public function getSthExecutePositional($entity)
    {
        $excecuteData = [];
        foreach ($this->fields as $field) {
            $excecuteData[] = $entity->$field;
        }
        return $excecuteData;
    }


    public function getSthExecuteNamed($mold)
    {
        $excecuteData = [];
        foreach ($this->getFields() as $field) {
            $excecuteData[':' . $field] = $mold->$field;
        }
        return $excecuteData;
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


    /**
     * binds values with unnamed placeholders, 1 2 3 instead of 0 1 2
     * @param  object $sth    the statement to bind to
     * @param  array $values basic array with values
     * @return bool | null         returns false if something goes wrong
     */
    public function bindValues($sth, $values)
    {
        if (! is_object($sth) || ! ($sth instanceof PDOStatement)) {
            return;
        }
        foreach ($values as $key => $value) {
            $correctedKey = $key + 1;
            $this->bindValue($sth, $correctedKey, $value);
        }
    }


    /**
     * binds a single value and guesses the type
     * good because it guesses the type
     * @param  object $sth
     * @param  int|string $key
     * @param  all $value
     */
    public function bindValue($sth, $key, $value)
    {
        if (is_int($value)) {
            $sth->bindValue($key, $value, \PDO::PARAM_INT);
        } elseif (is_bool($value)) {
            $sth->bindValue($key, $value, \PDO::PARAM_BOOL);
        } elseif (is_null($value)) {
            $sth->bindValue($key, $value, \PDO::PARAM_NULL);
        } elseif (is_string($value)) {
            $sth->bindValue($key, $value, \PDO::PARAM_STR);
        }
    }
}
