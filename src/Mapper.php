<?php

namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
abstract class Mapper extends \Mwyatt\Core\Data // implements \Mwyatt\Core\ModelInterface
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


    /**
     * identifies which object to store results in from a read
     * '\\Mwyatt\\Core\\Entity\\Foo'
     * @var string
     */
    protected $entity;


    /**
     * comprehensive list of database fields for use when building queries
     * lazily
     * @var array
     */
    protected $fields;


    /**
     * must connect to the database on first model build if not already
     * any other database access classes?
     */
    public function __construct(\Mwyatt\Core\DatabaseInterface $database)
    {

        // connect if not
        if (!$database->dbh) {
            $database->connect();
        }

        // use already connected
        $this->database = $database;
    }


    /**
     * data = ids created [1, 5, 2]
     * @param  array $entities
     * @return object
     */
    public function create(array $entities)
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
        $sth = $this->database->dbh->prepare("{$this->getSqlSelect()}");

        // mode
        $sth->setFetchMode(\PDO::FETCH_CLASS, $this->getEntity());

        // execute
        $sth->execute();

        // fetch
        $this->setData($sth->fetchAll());

        // instance
        return $this->getData();
    }


    /**
     * read single column with multiple values
     * @param  array  $values
     * @param  string $columnName
     * @return object
     */
    public function readColumn(array $values, $columnName = 'id')
    {

        // query
        $sth = $this->database->dbh->prepare("
            {$this->getSqlSelect()}
            where {$columnName} = :value
        ");

        // mode
        $sth->setFetchMode(\PDO::FETCH_CLASS, $this->getEntity());

        // loop prepared statement
        $results = [];
        foreach ($values as $value) {
            $this->bindValue($sth, ':value', $value);
            $sth->execute();
            while ($result = $sth->fetch()) {
                $results[] = $result;
            }
        }

        // instance
        $this->setData($results);
        return $this->getData();
    }


    /**
     * uses the passed properties to build named prepared statement
     * data = array of id => status [1, 0]
     * struggling to use the id as it may not always be there..
     * @param  array  $molds
     * @param  string $by    defines the column to update by
     * @return int
     */
    public function update(array $entities, $column = 'id')
    {
        $result = [];

        // statement
        $statement = [];
        $statement[] = 'update';
        $statement[] = $this->getTableName();
        $statement[] = 'set';

        // getting field = :field, array
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
            $entityPropertyValue = $this->getEntityPropertyValue($entity, $column);
            foreach ($this->getSthExecuteNamed($entity) as $key => $value) {
                $this->bindValue($sth, $key, $value);
            }
            $this->bindValue($sth, 'column', $entityPropertyValue);
            $sth->setFetchMode(\PDO::FETCH_CLASS, $this->getEntity());
            $sth->execute();
            $result[$entityPropertyValue] = $sth->rowCount();
        }
        $this->setData($result);
        return $this;
    }


    /**
     * uses where property to build delete statement
     * improved to allow entities to be passed
     * data = array of id => status [1 => 1, 2 => 0]
     * @param  array  $properties
     * @return int
     */
    public function delete(array $entities, $column = 'id')
    {
        $result = [];

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
            $entityPropertyValue = $this->getEntityPropertyValue($entity, $column);
            $this->bindValue($sth, 1, $entityPropertyValue);
            $sth->execute();
            $result[$entityPropertyValue] = $sth->rowCount();
        }

        // return
        $this->setData($result);
        return $this;
    }


    /**
     * builds a generic select statement and returns
     * select (column, column) from (table_name)
     * @return string
     */
    protected function getSqlSelect()
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
    protected function getSqlFields()
    {
        return implode(', ', $this->fields);
    }


    /**
     * @return string ?, ?, ? of all fields
     */
    protected function getSqlPositionalPlaceholders()
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
    protected function getSthExecutePositional($entity)
    {
        $excecuteData = [];
        foreach ($this->fields as $property) {
            $excecuteData[] = $this->getEntityPropertyValue($entity, $property);
        }
        return $excecuteData;
    }


    /**
     * get all properties and values with a named key
     * @param  object $entity
     * @return array  array[:property] = value
     */
    protected function getSthExecuteNamed($entity)
    {
        $excecuteData = [];
        foreach ($this->getFields() as $property) {
            $excecuteData[':' . $property] = $this->getEntityPropertyValue($entity, $property);
        }
        return $excecuteData;
    }


    /**
     * get a property from an entity
     * some may be protected and would need to use the accessor
     * method
     * @param  object $entity
     * @param  string $property
     * @return mixed
     */
    protected function getEntityPropertyValue($entity, $property)
    {
        $getMethod = 'get' . ucfirst($property);
        if (method_exists($entity, $getMethod)) {
            return $entity->{$getMethod}();
        } else {
            return $entity->$property;
        }
    }


    /**
     * binds values with unnamed placeholders, 1 2 3 instead of 0 1 2
     * @param  object $sth    the statement to bind to
     * @param  array $values basic array with values
     * @return bool | null         returns false if something goes wrong
     */
    protected function bindValues($sth, $values)
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
    protected function bindValue($sth, $key, $value)
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


    /**
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }


    public function getEntityObject()
    {
        return new $this->entity;
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
     * get the entity properties
     * @return array [id, name]
     */
    public function getEntityProperties()
    {
        return array_keys(get_class_vars($this->getEntity()));
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
}
