<?php

namespace Mwyatt\Core;

abstract class AbstractMapper
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


    protected $fetchType = \PDO::FETCH_CLASS;


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


    public function setFetchType($type)
    {
        $this->fetchType = $type;
    }


    protected function getModelClass()
    {
        return $this->model;
    }


    public function getIterator(array $models)
    {
        return new \Mwyatt\Core\ModelIterator($models);
    }


    public function findAll()
    {
        $sql = ['select', '*', 'from', $this->table];

        $this->adapter->prepare(implode(' ', $sql));
        $this->adapter->execute();
     
        $models = $this->adapter->fetchAll($this->fetchType, $this->model);

        return $this->getIterator($models);
    }


    public function findByIds(array $ids)
    {
        $sql = ['select', '*', 'from', $this->table, 'where', '`id`', '= ?'];
        $this->adapter->prepare(implode(' ', $sql));
        $results = [];
        
        foreach ($ids as $id) {
            $this->adapter->bindParam(1, $id, $this->adapter->getParamInt());
            $this->adapter->execute();
            if ($model = $this->adapter->fetch($this->fetchType, $this->model)) {
                $results[] = $model;
            }
        }
        return $this->getIterator($results);
    }


    /**
     * is this ever a good idea?
     * @param  object $model
     * @param  array  $cols
     * @return object        $model
     */
    public function lazyPersist(\Mwyatt\Core\ModelInterface $model, array $cols)
    {
        $hasId = $model->get('id');
        $sql = [$hasId ? 'update' : 'insert into'];
        $sql[] = $this->table;
        $executeData = [];
        $sqlCols = [];
        
        if ($hasId) {
            $sql[] = 'set';
            foreach ($cols as $col) {
                $sqlCols[] = "`$col` = :$col";
            }
        } else {
            $sql[] = '(';
            foreach ($cols as $col) {
                $sqlCols[] = "`$col`";
            }
            $sql[] = implode(', ', $sqlCols);
            $sql[] = ') values (';
            $sqlCols = [];
            foreach ($cols as $col) {
                $sqlCols[] = ":$col";
            }
        }

        $sql[] = implode(', ', $sqlCols);

        if ($hasId) {
            $sql[] = "where `id` = :id";
        } else {
            $sql[] = ')';
        }

        try {
            $this->adapter->prepare(implode(' ', $sql));
            if ($hasId) {
                $this->adapter->bindParam(":id", $model->get('id'));
            }
            foreach ($cols as $col) {
                $this->adapter->bindParam(":$col", $model->get($col));
            }
            $this->adapter->execute();
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        // potential error
        if (!$this->adapter->getRowCount()) {
            return 'No rows were affected.';
        }

        if (!$hasId) {
            $model->setId($this->adapter->getLastInsertId());
        }

        return $model;
    }


    public function delete(array $models)
    {
        $sql = ['delete', 'from', $this->table, 'where id = ?'];
        $rowCount = 0;

        $this->adapter->prepare(implode(' ', $sql));

        foreach ($models as $model) {
            $this->adapter->bindParam(1, $model->get('id'), $this->adapter->getParamInt());
            $this->adapter->execute();
            $rowCount += $this->adapter->getRowCount();
        }

        return $rowCount == count($models);
    }
}
