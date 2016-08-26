<?php

namespace Mwyatt\Core;

abstract class ServiceAbstract implements \IteratorAggregate
{


    protected $mapperFactory;


    protected $modelFactory;


    protected $collection = [];


    public function __construct(
        \Mwyatt\Core\MapperFactory $mapperFactory,
        \Mwyatt\Core\ModelFactory $modelFactory
    ) {
        $this->mapperFactory = $mapperFactory;
        $this->modelFactory = $modelFactory;
        $this->collection = new \Mwyatt\Core\ObjectIterator;
    }


    public function getIterator()
    {
        return new ArrayIterator($this->collection);
    }


    public function add($item)
    {
        $this->collection[] = $item;
    }


    public function getModel($name)
    {
        return $this->modelFactory->get($name);
    }


    public function getMapper($name)
    {
        return $this->mapperFactory->get($name);
    }


    public function findAll()
    {
        $mapper = $this->mapperFactory->get($this->getClassName());
        return $mapper->findAll();
    }


    public function getClassName()
    {
        return end(explode('\\', get_class($this)));
    }
}
