<?php

namespace Mwyatt\Core;

abstract class AbstractService
{


    /**
     * how will this work?
     * @var object iterator
     */
    protected $collection;


    protected $mapperFactory;


    protected $modelFactory;


    public function __construct(
        \Mwyatt\Core\MapperFactory $mapperFactory,
        \Mwyatt\Core\ModelFactory $modelFactory
    ) {
        $this->mapperFactory = $mapperFactory;
        $this->modelFactory = $modelFactory;
    }


    public function getModel($name)
    {
        return $this->modelFactory->get($name);
    }


    public function getMapper($name)
    {
        return $this->mapperFactory->get($name);
    }


    public function findById($id)
    {
        $mapper = $this->mapperFactory->get($this->getRelativeClassName());
        $models = $mapper->findByIds([$id]);
        return $models->current();
    }


    public function findAll()
    {
        $mapper = $this->mapperFactory->get($this->getRelativeClassName());
        $all = $mapper->findAll();
        $this->collection = $all;
        return $all;
    }


    public function getRelativeClassName()
    {
        return str_replace('Mwyatt\\Core\\Service\\', '', get_class($this));
    }
}
