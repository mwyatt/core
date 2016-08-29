<?php

namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 */
class ServiceFactory extends \Mwyatt\Core\Factory
{


    protected $mapperFactory;


    protected $modelFactory;


    public function get($name)
    {
        $namespace = $this->defaultNamespace . $name;
        return new $namespace(
            $this->mapperFactory,
            $this->modelFactory
        );
    }
}
