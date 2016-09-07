<?php

namespace Mwyatt\Core;

abstract class AbstractMapper implements \Mwyatt\Core\MapperInterface
{


    /**
     * the adapter instance
     * @var object
     */
    protected $adapter;


    /**
     * the name of table being read (guessed using class)
     * @var string
     */
    protected $table;


    /**
     * namespace of the model (guessed using class)
     */
    protected $model;


    public function __construct(\Mwyatt\Core\DatabaseInterface $adapter)
    {
        $this->adapter = $adapter;
        $relClassName = $this->getRelativeClassName();
        $this->table = lcfirst(str_replace('\\', '', $relClassName));
        $this->model = '\\Mwyatt\\Core\\Model\\' . $relClassName;
    }


    public function beginTransaction()
    {
        return $this->adapter->beginTransaction();
    }


    public function rollBack()
    {
        return $this->adapter->rollBack();
    }


    public function commit()
    {
        return $this->adapter->commit();
    }


    public function getRelativeClassName()
    {
        return str_replace('Mwyatt\\Core\\Mapper\\', '', get_class($this));
    }


    protected function getModelClass()
    {
        return $this->model;
    }


    public function getModel()
    {
        return new $this->getModelClass();
    }


    /**
     * get the iterator specific to this class or a custom one if required
     */
    public function getIterator(array $models, $requestedClassPath = '')
    {
        $basePath = '\\Mwyatt\\Core\\Model';
        $endPath = 'Iterator';

        if ($requestedClassPath) {
            $possiblePath = $basePath . '\\' . $this->getRelativeClassName() . $endPath;
            if (!class_exists($possiblePath)) {
                throw new \Exception("Unable to find iterator '$possiblePath'");
            }
        }

        $possiblePath = $basePath . '\\' . $this->getRelativeClassName() . $endPath;

        if (class_exists($possiblePath)) {
            $chosenPath = $possiblePath;
        } else {
            $chosenPath = $basePath . $endPath;
        }

        return new $chosenPath($models);
    }


    /**
     * exceptions should be handled at the mapper level, not service?
     * @return iterator
     */
    public function findAll()
    {
        try {
            $models = [];
            $this->adapter->prepare("select * from `{$this->table}`");
            $this->adapter->execute();
            while ($data = $this->adapter->fetch($this->adapter->getFetchTypeAssoc())) {
                $models[] = $this->createModel($data);
            }
            return $this->getIterator($models);
        } catch (\PDOException $e) {
            throw new \Mwyatt\Core\DatabaseException("Problem while communicating with database.");
        }
    }


    public function findByIds(array $ids)
    {
        try {
            $models = [];
            $this->adapter->prepare("select * from `{$this->table}` where `id` = ?");
            foreach ($ids as $id) {
                $this->adapter->bindParam(1, $id, $this->adapter->getParamInt());
                $this->adapter->execute();
                if ($data = $this->adapter->fetch($this->adapter->getFetchTypeAssoc())) {
                    $models[] = $this->createModel($data);
                }
            }
            return $this->getIterator($models);
        } catch (\PDOException $e) {
            throw new \Mwyatt\Core\DatabaseException("Problem while communicating with database.");
        }
    }


    /**
     * may not be needed?
     * @param  array  $keys
     * @param  array  $data
     */
    public function testArrayKeys(array $keys, array $data)
    {
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new \Exception("Missing data key '$key'.");
            }
        }
    }


    /**
     * builds insert statement using cols provided
     * @param  array  $cols 'name', 'another'
     * @return string       built insert sql
     */
    public function getInsertGenericSql(array $cols)
    {
        $sql = ['insert into', $this->table, '('];
        $sqlCols = [];
        foreach ($cols as $col) {
            $sqlCols[] = "`$col`";
        }
        $sql[] = implode(', ', $sqlCols);
        $sql[] = ') values (';
        $sqlCols = [];
        foreach ($cols as $col) {
            $sqlCols[] = ":$col";
        }
        $sql[] = implode(', ', $sqlCols);
        $sql[] = ')';
        return implode(' ', $sql);
    }


    /**
     * builds update statement using cols provided
     * @param  array  $cols 'name', 'another'
     * @return string       built update sql
     */
    public function getUpdateGenericSql(array $cols)
    {
        $sql = ['update', $this->table, 'set'];
        $sqlCols = [];
        foreach ($cols as $col) {
            $sqlCols[] = "`$col` = :$col";
        }
        $sql[] = implode(', ', $sqlCols);
        $sql[] = "where `id` = :id";
        return implode(' ', $sql);
    }


    public function delete($models)
    {
        $sql = ['delete', 'from', $this->table, 'where id = ?'];
        $rowCount = 0;

        $this->adapter->prepare(implode(' ', $sql));

        foreach ($models as $model) {
            $this->adapter->bindParam(1, $model->get('id'), $this->adapter->getParamInt());
            $this->adapter->execute();
            $rowCount += $this->adapter->getRowCount();
        }

        if ($rowCount !== count($models)) {
            throw new \PDOException('Unexpected response from storage adapter.');
        }
    }
}
