<?php

namespace Mwyatt\Core\Factory;

class Mapper extends \Mwyatt\Core\AbstractFactory
{
    protected $defaultNamespace = 'Mwyatt\\Core\\Mapper\\';
    protected $pimpleContainer;
    protected $modelFactory;
    protected $iteratorFactory;


    public function __construct(
        \Pimple\Container $pimpleContainer,
        \Mwyatt\Core\Factory\Model $modelFactory,
        \Mwyatt\Core\Factory\Iterator $iteratorFactory
    ) {
        $this->pimpleContainer = $pimpleContainer;
        $this->modelFactory = $modelFactory;
        $this->iteratorFactory = $iteratorFactory;
    }


    public function get($name)
    {
        $namespace = $this->getDefaultNamespaceAbs($name);
        return new $namespace(
            $this->pimpleContainer,
            $this->modelFactory,
            $this->iteratorFactory
        );
    }
}
