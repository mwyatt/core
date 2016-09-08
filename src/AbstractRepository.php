<?php

namespace Mwyatt\Core;

abstract class AbstractRepository
{


    protected $defaultNamespace = 'Mwyatt\\Core\\Repository\\';
    protected $mapperFactory;


    public function __construct(\Mwyatt\Core\Factory\Mapper $mapperFactory)
    {
        $this->mapperFactory = $mapperFactory;
    }


    protected function getMapper($name)
    {
        return $this->mapperFactory->get($name);
    }


    protected function getRelativeClassName()
    {
        return str_replace($this->defaultNamespace, '', get_class($this));
    }


    protected function getMapperLazy()
    {
        return $this->mapperFactory->get($this->getRelativeClassName());
    }


    public function findAll()
    {
        $mapper = $this->getMapperLazy();
        $all = $mapper->findAll();
        return $all;
    }


    public function findById($id)
    {
        $mapper = $this->getMapperLazy();
        $models = $mapper->findByIds([$id]);
        return $models->current();
    }


    public function findByIds(array $ids)
    {
        $mapper = $this->getMapperLazy();
        $models = $mapper->findByIds($ids);
        return $models;
    }


    public function insert(array $data)
    {
        $mapper = $this->getMapperLazy();
        $mapper->insert($data);
    }


    public function update(\Mwyatt\Core\ModelInterface $model)
    {
        $mapper = $this->getMapperLazy();
        $mapper->update($model);
    }


    public function delete($models)
    {
        $mapper = $this->getMapperLazy();
        $mapper->delete($models);
    }
}
