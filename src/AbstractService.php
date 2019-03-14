<?php

namespace Mwyatt\Core;

abstract class AbstractService
{


    protected function getMapper($name)
    {
        $mapperFactory = $this->getService('MapperFactory');
        return $mapperFactory->get($name);
    }
}
