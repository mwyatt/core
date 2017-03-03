<?php

namespace Mwyatt\Core;

interface MapperInterface
{
    public function __construct(
        \Mwyatt\Core\DatabaseInterface $adapter,
        \Mwyatt\Core\Factory\Model $modelFactory,
        \Mwyatt\Core\Factory\Iterator $iteratorFactory
    );
    public function persist(\Mwyatt\Core\ModelInterface $model);
    public function getModel($name = null);
    public function getModelClassAbs($name = null);
    public function getIterator($models = [], $requestedClassPath = '');
    public function findAll();
    public function findByIds(array $ids);
    public function getInsertGenericSql(array $cols);
    public function getUpdateGenericSql(array $cols);
    public function deleteById(\Mwyatt\Core\ModelInterface $model);
}
