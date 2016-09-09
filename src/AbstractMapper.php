<?php

namespace Mwyatt\Core;

abstract class AbstractMapper implements \Mwyatt\Core\MapperInterface
{


    protected $defaultNamespace = 'Mwyatt\\Core\\Mapper\\';
    protected $adapter;
    protected $modelFactory;
    protected $iteratorFactory;


    public function __construct(\Mwyatt\Core\DatabaseInterface $adapter, \Mwyatt\Core\Factory\Model $modelFactory)
    {
        $this->adapter = $adapter;
        $this->modelFactory = $modelFactory;
    }


    protected function getTableNameLazy()
    {
        return lcfirst(str_replace('\\', '', $this->getRelativeClassName()));
    }


    protected function getRelativeClassName()
    {
        return str_replace($this->defaultNamespace, '', get_class($this));
    }


    protected function getModelClassLazy()
    {
        return $this->modelFactory->getDefaultNamespace() . $this->getRelativeClassName();
    }


    protected function getModelLazy(array $data)
    {
        return $this->getModel($this->getRelativeClassName(), $data);
    }


    protected function getModel($name, array $data)
    {
        return $this->modelFactory->get($name, $data);
    }


    /**
     * get the iterator specific to this class or a custom one if required
     */
    public function getIterator($models = [], $requestedClassPath = '')
    {
        $basePath = '\\Mwyatt\\Core\\Iterator\\Model';

        if ($requestedClassPath) {
            $possiblePath = $basePath . '\\' . $requestedClassPath;
            if (!class_exists($possiblePath)) {
                throw new \Exception("Unable to find iterator '$possiblePath'");
            }
        } else {
            $possiblePath = $basePath . '\\' . $this->getRelativeClassName();
        }

        if (class_exists($possiblePath)) {
            $chosenPath = $possiblePath;
        } else {
            $chosenPath = $basePath;
        }

        return new $chosenPath($models);
    }


    public function findAll()
    {
        $models = [];
        $this->adapter->prepare("select * from `{$this->getTableNameLazy()}`");
        $this->adapter->execute();
        while ($data = $this->adapter->fetch()) {
            $models[] = $this->getModelLazy($data);
        }
        return $this->getIterator($models);
    }


    public function findByIds(array $ids)
    {
        $models = [];
        $this->adapter->prepare("select * from `{$this->getTableNameLazy()}` where `id` = ?");
        foreach ($ids as $id) {
            $this->adapter->bindParam(1, $id, $this->adapter->getParamInt());
            $this->adapter->execute();
            if ($data = $this->adapter->fetch()) {
                $models[] = $this->getModelLazy($data);
            }
        }
        return $this->getIterator($models);
    }


    /**
     * builds insert statement using cols provided
     * @param  array  $cols 'name', 'another'
     * @return string       built insert sql
     */
    public function getInsertGenericSql(array $cols)
    {
        $sql = ['insert into', $this->getTableNameLazy(), '('];
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
        $sql = ['update', $this->getTableNameLazy(), 'set'];
        $sqlCols = [];
        foreach ($cols as $col) {
            $sqlCols[] = "`$col` = :$col";
        }
        $sql[] = implode(', ', $sqlCols);
        $sql[] = "where `id` = :id";
        return implode(' ', $sql);
    }


    public function deleteById(\Mwyatt\Core\AbstractIterator $models)
    {
        $sql = ['delete', 'from', $this->getTableNameLazy(), 'where id = ?'];
        $rowCount = 0;
        $modelCount = count($models);
        $this->adapter->prepare(implode(' ', $sql));
        foreach ($models as $model) {
            $this->adapter->bindParam(1, $model->get('id'), $this->adapter->getParamInt());
            $this->adapter->execute();
            $rowCount += $this->adapter->getRowCount();
        }
        if ($rowCount !== $modelCount) {
            throw new \PDOException("Deleted rowCount $rowCount does not match expected $modelCount.");
        }
    }
}
