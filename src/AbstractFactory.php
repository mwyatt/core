<?php

namespace Mwyatt\Core;

abstract class AbstractFactory implements \Mwyatt\Core\FactoryInterface
{
    protected $defaultNamespace;


    protected function getDefaultNamespace($append = '')
    {
        return $this->defaultNamespace . $append;
    }


    /**
     * inside a project this will be overridden
     * @param string $value Mwyatt\Core\Mapper\
     */
    public function setDefaultNamespace($value)
    {
        $this->defaultNamespace = $value;
    }


    /**
     * exposed so mapper can do clever things
     */
    public function getDefaultNamespaceAbs($append = '')
    {
        $namespace = '\\' . $this->getDefaultNamespace($append);
        if (!class_exists($namespace)) {
            throw new \Exception("'$namespace' does not exist.");
        }
        return $namespace;
    }
}
