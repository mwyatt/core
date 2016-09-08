<?php

namespace Mwyatt\Core;

abstract class AbstractMapper implements \Mwyatt\Core\MapperInterface
{


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


    protected function getRelativeClassName()
    {
        return str_replace('Mwyatt\\Core\\Mapper\\', '', get_class($this));
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


    /**
     * exceptions should be handled at the mapper level, not service?
     * @return iterator
     */
    public function findAll()
    {
        try {
            $models = [];
            $this->adapter->prepare("select * from `{$this->getTableNameLazy()}`");
            $this->adapter->execute();
            while ($data = $this->adapter->fetch($this->adapter->getFetchTypeAssoc())) {
                $models[] = $this->getModelLazy($data);
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
            $this->adapter->prepare("select * from `{$this->getTableNameLazy()}` where `id` = ?");
            foreach ($ids as $id) {
                $this->adapter->bindParam(1, $id, $this->adapter->getParamInt());
                $this->adapter->execute();
                if ($data = $this->adapter->fetch($this->adapter->getFetchTypeAssoc())) {
                    $models[] = $this->getModelLazy($data);
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


    public function delete($models)
    {
        $sql = ['delete', 'from', $this->getTableNameLazy(), 'where id = ?'];
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
