<?php

namespace Mwyatt\Core;

abstract class AbstractMapper implements \Mwyatt\Core\MapperInterface
{
    protected $adapter;
    protected $modelFactory;
    protected $iteratorFactory;
    protected $tableName;
    protected $protectedCols = ['id'];
    protected $publicCols = [];


    public function __construct(
        \Mwyatt\Core\DatabaseInterface $adapter,
        \Mwyatt\Core\Factory\Model $modelFactory,
        \Mwyatt\Core\Factory\Iterator $iteratorFactory
    ) {
    
        $this->adapter = $adapter;
        $this->modelFactory = $modelFactory;
        $this->iteratorFactory = $iteratorFactory;
    }


    public function persist(\Mwyatt\Core\ModelInterface $model)
    {
        if (strpos(get_class($model), $this->getRelativeClassName()) === false) {
            throw new \Exception('Incorrect model class name.');
        }
        $isUpdate = $model->get('id');
        $method = $isUpdate ? 'getUpdateGenericSql' : 'getInsertGenericSql';
        $this->adapter->prepare($this->$method(array_keys($this->publicCols)));
        foreach ($this->publicCols as $col => $type) {
            $this->adapter->bindParam(":$col", $model->get($col), $type);
        }
        if ($isUpdate) {
            $this->adapter->bindParam(":id", $model->get('id'), $this->adapter->getParamInt());
        }
        $this->adapter->execute();
        if (!$isUpdate) {
            $model->setId($this->adapter->getLastInsertId());
            if (!$this->adapter->getRowCount()) {
                throw new \Exception('Unexpected rowCount after insert.');
            }
        }
        return $this->adapter->getRowCount();
    }


    protected function getTableNameLazy()
    {
        if ($this->tableName) {
            return $this->tableName;
        } else {
            return lcfirst(str_replace('\\', '', $this->getRelativeClassName()));
        }
    }


    private function getDefaultNamespace()
    {
        $match = 'Mapper';
        $parts = explode($match, get_class($this));
        return reset($parts) . "$match\\";
    }


    protected function getRelativeClassName()
    {
        return str_replace($this->getDefaultNamespace(), '', get_class($this));
    }


    public function getModel($name = null)
    {
        return $this->modelFactory->get($name ? $name : $this->getRelativeClassName());
    }


    public function getModelClassAbs($name = null)
    {
        return $this->modelFactory->getDefaultNamespaceAbs($name ? $name : $this->getRelativeClassName());
    }


    /**
     * get the iterator specific to this class or a custom one if required
     */
    public function getIterator($models = [], $requestedClassPath = '')
    {
        $possiblePath = '';
        if ($requestedClassPath) {
            $possiblePath = $this->iteratorFactory->getDefaultNamespaceAbs($requestedClassPath);
            if (!class_exists($possiblePath)) {
                throw new \Exception("Unable to find iterator '$possiblePath'");
            }
        } else {
            try {
                $possiblePath = $this->iteratorFactory->getDefaultNamespaceAbs('Model\\' . $this->getRelativeClassName());
            } catch (\Exception $e) {
            }
        }
        if (class_exists($possiblePath)) {
            $chosenPath = $possiblePath;
        } else {
            $chosenPath = '\\Mwyatt\\Core\\Iterator\\Model';
        }
        rtrim($chosenPath, '\\');
        return new $chosenPath($models);
    }


    public function findAll()
    {
        $models = [];
        $this->adapter->prepare("select * from `{$this->getTableNameLazy()}`");
        $this->adapter->execute();
        while ($model = $this->adapter->fetch($this->adapter->getFetchTypeClass(), $this->getModelClassAbs())) {
            $models[] = $model;
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
            if ($model = $this->adapter->fetch($this->adapter->getFetchTypeClass(), $this->getModelClassAbs())) {
                $models[] = $model;
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


    public function deleteById(\Mwyatt\Core\ModelInterface $model)
    {
        $sql = ['delete', 'from', $this->getTableNameLazy(), 'where id = ?'];
        $this->adapter->prepare(implode(' ', $sql));
        $this->adapter->bindParam(1, $model->get('id'), $this->adapter->getParamInt());
        $this->adapter->execute();
        return $this->adapter->getRowCount();
    }
}
