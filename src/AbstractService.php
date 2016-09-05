<?php

namespace Mwyatt\Core;

abstract class AbstractService implements \Mwyatt\Core\ServiceInterface
{


    protected $mapperFactory;


    protected $modelFactory;


    public function __construct(\Mwyatt\Core\MapperFactory $mapperFactory, \Mwyatt\Core\ModelFactory $modelFactory) {
        $this->mapperFactory = $mapperFactory;
        $this->modelFactory = $modelFactory;
    }


    // public function getModel($name = null)
    // {
    //     return $this->modelFactory->get($name ? $name : $this->getRelativeClassName());
    // }


    public function getMapper($name)
    {
        return $this->mapperFactory->get($name);
    }


    public function createModel(array $data)
    {
        $mapper = $this->mapperFactory->get($this->getRelativeClassName());
        return $mapper->createModel($data);
    }


    public function update(\Mwyatt\Core\ModelInterface $model)
    {
        $mapper = $this->mapperFactory->get($this->getRelativeClassName());
        return $mapper->update($model);
    }


    public function findById($id)
    {
        $mapper = $this->mapperFactory->get($this->getRelativeClassName());
        $models = $mapper->findByIds([$id]);
        return $models->current();
    }


    public function findByIds(array $ids)
    {
        $mapper = $this->mapperFactory->get($this->getRelativeClassName());
        $models = $mapper->findByIds($ids);
        return $models;
    }


    public function findAll()
    {
        $mapper = $this->mapperFactory->get($this->getRelativeClassName());
        $all = $mapper->findAll();
        return $all;
    }


    public function getRelativeClassName()
    {
        return str_replace('Mwyatt\\Core\\Service\\', '', get_class($this));
    }
}
