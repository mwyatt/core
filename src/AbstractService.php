<?php

namespace Mwyatt\Core;

abstract class AbstractService
{
    protected $pimpleContainer;
    protected $mapperFactory;


    public function __construct(
        \Pimple\Container $pimpleContainer
    ) {
        $this->pimpleContainer = $pimpleContainer;
        $this->mapperFactory = $this->getService('MapperFactory');
    }


    public function getService($name)
    {
        return $this->pimpleContainer[$name];
    }


    public function getMapper($name)
    {
        return $this->mapperFactory->get($name);
    }
}
