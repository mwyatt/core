<?php

namespace Mwyatt\Core;

abstract class AbstractService
{
    protected $mapperFactory;


    public function __construct(
        \Mwyatt\Core\FactoryInterface $mapperFactory
    ) {
        $this->mapperFactory = $mapperFactory;
    }


    public function getMapper($name)
    {
        return $this->mapperFactory->get($name);
    }
}
